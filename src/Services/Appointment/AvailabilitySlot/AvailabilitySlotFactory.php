<?php

namespace App\Services\Appointment\AvailabilitySlot;

use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride; // Presumendo sia il tipo corretto
use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate; // Presumendo sia il tipo corretto
use App\Entity\Trainer\Trainer;
use App\Model\Form\Appointment\AvailabilityOverride\AvailabilityOverrideFilter;
use App\Services\Appointment\AvailabilityOverride\AvailabilityOverrideLister;
use App\Services\Appointment\ScheduleTemplate\ScheduleTemplateLister;
use App\Services\Trainer\TrainerLister;
use App\Services\Utils\Helper\DoctrineHelper;
use DateTime;

class AvailabilitySlotFactory
{
    private const SLOT_GENERATION_DAYS = 21; // Numero di giorni per cui generare gli slot

    public function __construct(
        private readonly TrainerLister $trainerLister,
        private readonly DoctrineHelper $doctrineHelper,
        private readonly AvailabilityOverrideLister $overrideLister,
        private readonly ScheduleTemplateLister $scheduleTemplateLister,
    ) {}

    /**
     * Genera gli slot di disponibilità per i trainer attivi per un periodo predefinito.
     */
    public function generateAvailabilitySlot(): void
    {
        $generationStartDate = (new DateTime())->setTime(0, 0, 0); // Inizio oggi a mezzanotte
        $generationEndDate = (clone $generationStartDate)->modify('+' . self::SLOT_GENERATION_DAYS . ' days');

        /** @var Trainer[] $activeTrainers */
        $activeTrainers = $this->trainerLister->getAllActiveTrainers();

        foreach ($activeTrainers as $trainer) {
            $this->processTrainerSlots($trainer, $generationStartDate, $generationEndDate);
            // flush per trainer per ottimizzare l'uso della memoria durante processi lunghi
            $this->doctrineHelper->flush();
            $this->doctrineHelper->getEntityManager()->clear(); // Opzionale: pulisce l'entity manager per liberare più memoria
        }
    }

    /**
     * Elabora e genera gli slot per un singolo trainer per il periodo specificato.
     */
    private function processTrainerSlots(Trainer $trainer, DateTime $periodStart, DateTime $periodEnd): void
    {
        $scheduleTemplates = $this->scheduleTemplateLister->getTrainersScheduleTemplates(user: $trainer->getUser());

        $currentDate = clone $periodStart;
        while ($currentDate < $periodEnd) { // Il ciclo si ferma un giorno prima di $periodEnd
            $overridesForDate = $this->getOverridesForDate($trainer, $currentDate);

            foreach ($scheduleTemplates as $template) {
                if ((int)$currentDate->format('N') === $template->getWeekDay()) {
                    $this->generateSlotsForDayFromTemplate($trainer, $template, $currentDate, $overridesForDate);
                }
            }
            $currentDate->modify('+1 day');
        }
    }

    /**
     * Recupera gli override di disponibilità per un dato trainer e una data specifica.
     * @return AvailabilityOverride[]
     */
    private function getOverridesForDate(Trainer $trainer, DateTime $date): array
    {
        return $this->overrideLister->getTrainersAvailabilityOverrides(
            (new AvailabilityOverrideFilter())->setDate($date),
            $trainer->getUser()
        );
    }

    /**
     * Genera slot per un giorno specifico basandosi su un template di schedulazione.
     */
    private function generateSlotsForDayFromTemplate(
        Trainer $trainer,
        ScheduleTemplate $template,
        DateTime $date,
        array $overridesForDate
    ): void {
        $templateStartTimeOnDate = $this->createDateTimeFromDateAndTime($date, $template->getStartTime());
        $templateEndTimeOnDate = $this->createDateTimeFromDateAndTime($date, $template->getEndTime());
        $slotBlockMinutes = $template->getBlockTime();

        if ($slotBlockMinutes <= 0) {
            // Previene loop infiniti o divisioni per zero se blockTime non è valido.
            return;
        }

        $currentSlotStart = clone $templateStartTimeOnDate;
        while ($currentSlotStart < $templateEndTimeOnDate) {
            $currentSlotEnd = (clone $currentSlotStart)->modify('+' . $slotBlockMinutes . ' minutes');

            if ($currentSlotEnd > $templateEndTimeOnDate) {
                // Lo slot eccede l'orario di fine del template.
                break;
            }

            $this->createSlotIfMissing($trainer, $date, $currentSlotStart, $currentSlotEnd, $overridesForDate);
            $currentSlotStart = $currentSlotEnd; // Avanza all'inizio del prossimo slot.
        }
    }

    /**
     * Crea un nuovo slot se non ne esiste già uno per i parametri forniti,
     * determinando la sua attività in base agli override.
     */
    private function createSlotIfMissing(
        Trainer $trainer,
        DateTime $dateForSlot, // Data normalizzata a 00:00:00 per lo slot
        DateTime $slotStartTime, // DateTime completo per l'inizio dello slot
        DateTime $slotEndTime,   // DateTime completo per la fine dello slot
        array $overridesForDate
    ): void {
        // La data passata a findExistingSlot e per impostare lo slot deve essere normalizzata (solo Y-m-d)
        // se il campo 'date' in AvailabilitySlot è di tipo 'date'.
        // $dateForSlot è già normalizzata (setTime(0,0,0)) dall'inizio.

        $existingSlot = $this->findExistingSlot($trainer, $dateForSlot, $slotStartTime, $slotEndTime);

        if ($existingSlot === null) {
            $newSlot = (new AvailabilitySlot())
                ->setTrainer($trainer)
                ->setDate(clone $dateForSlot) // Usa la data normalizzata per il campo 'date'
                ->setStartTime(clone $slotStartTime) // Ora di inizio precisa (include data e ora)
                ->setEndTime(clone $slotEndTime);   // Ora di fine precisa (include data e ora)

            $isActive = $this->calculateSlotActivity($newSlot, $overridesForDate);
            $newSlot->setActive($isActive);

            $this->doctrineHelper->persist($newSlot);
        }
    }

    /**
     * Trova uno slot di disponibilità esistente.
     * Nota: la query deve corrispondere a come sono memorizzati i campi 'date', 'startTime', 'endTime'.
     * Se 'startTime'/'endTime' sono campi 'time' nel DB, Doctrine gestirà la conversione da DateTime.
     * Se sono 'datetime', allora la data e l'ora complete devono corrispondere.
     */
    private function findExistingSlot(Trainer $trainer, DateTime $date, DateTime $startTime, DateTime $endTime): ?AvailabilitySlot
    {
        return $this->doctrineHelper
            ->getRepository(AvailabilitySlot::class)
            ->findOneBy([
                'trainer' => $trainer,
                'date' => $date,                 // Es. 2023-10-01 00:00:00
                'startTime' => $startTime,       // Es. 2023-10-01 09:00:00
                'endTime' => $endTime,           // Es. 2023-10-01 09:30:00
            ]);
    }

    /**
     * Determina se uno slot è attivo basandosi sugli override forniti per la data dello slot.
     */
    private function calculateSlotActivity(AvailabilitySlot $slot, array $overridesForDate): bool
    {
        foreach ($overridesForDate as $override) {
            /** @var AvailabilityOverride $override */
            if ($override->isFullDayOverride()) {
                return false; // Override per l'intera giornata, slot non attivo.
            }

            // Verifica di sovrapposizione per override parziali.
            $slotActualStartTime = $slot->getStartTime();
            $slotActualEndTime = $slot->getEndTime();

            // Costruisci i DateTime effettivi dell'override nella data dello slot.
            $overrideEffectiveStartTime = $this->createDateTimeFromDateAndTime($slot->getDate(), $override->getStartTime());
            $overrideEffectiveEndTime = $this->createDateTimeFromDateAndTime($slot->getDate(), $override->getEndTime());

            // Logica standard di sovrapposizione: (StartA < EndB) AND (EndA > StartB)
            $isOverlapping = ($slotActualStartTime < $overrideEffectiveEndTime) &&
                ($slotActualEndTime > $overrideEffectiveStartTime);

            if ($isOverlapping) {
                // Commento originale: # formatto la data così mi assicuro che venga fatta la verifica solo sull'orario
                // Il confronto robusto tra oggetti DateTime (allineati alla stessa data) sostituisce
                // il precedente metodo di formattazione in stringa 'H:i' (che era 'H:m' per errore).
                return false; // Sovrapposizione trovata, slot non attivo.
            }
        }
        return true; // Nessun override impattante trovato, slot attivo.
    }

    /**
     * Helper per creare un oggetto DateTime combinando una data specifica (Y-m-d)
     * con l'ora (H:i:s) presa da un altro oggetto DateTime ($timeProvider).
     */
    private function createDateTimeFromDateAndTime(DateTime $datePart, DateTime $timeProvider): DateTime
    {
        return (clone $datePart)->setTime(
            (int)$timeProvider->format('H'),
            (int)$timeProvider->format('i'),
            (int)$timeProvider->format('s') // Includiamo i secondi per precisione
        );
    }
}
