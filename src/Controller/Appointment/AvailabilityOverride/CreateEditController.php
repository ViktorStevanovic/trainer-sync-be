<?php

namespace App\Controller\Appointment\AvailabilityOverride;

use App\Controller\Controller;
use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Error\ErrorCodeEnum;
use App\Form\Appointment\AvailabilityOverride\AvailabilityOverrideType;
use App\Security\Voter\Appointment\AvailabilityOverride\CanViewAvailabilityOverrideVoter;
use App\Services\Appointment\AvailabilityOverride\AvailabilityOverrideManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CreateEditController extends Controller
{
    public function __construct(
        private readonly AvailabilityOverrideManager $availabilityOverrideManager
    ) {}

    #[Route(path: '/availability-override', methods: ['POST'])]
    public function createAvailabilityOverride(Request $request): JsonResponse
    {
        $this->beginTransaction();
        try {
            $availabilityOverride = new AvailabilityOverride();
            $availabilityOverride->setTrainer($this->getUser()->getTrainer());

            $form = $this->createForm(AvailabilityOverrideType::class, $availabilityOverride);
            $form->submit($request->request->all());

            if (!$form->isValid()) {
                return $this->renderSerializedFormErrors($form);
            }


            $this->save($availabilityOverride);
            $this->availabilityOverrideManager->createAvailabilityOverride($availabilityOverride);
            $this->commit();

            return $this->renderEmptyResponse();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
    }

    #[Route(path: '/availability-override/{availabilityOverride}', requirements: ['availabilityOverride' => '\d+'], methods: ['PUT'])]
    #[IsGranted(
        attribute: CanViewAvailabilityOverrideVoter::CAN_VIEW_AVAILABILITY_OVERRIDE,
        subject: 'availabilityOverride',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function editAvailabilityOverride(Request $request, AvailabilityOverride $availabilityOverride): JsonResponse
    {
        $this->beginTransaction();
        try {
            // Cloniamo l'override prima di modificarlo, per passare la versione vecchia al manager
            $oldOverride = clone $availabilityOverride;

            $form = $this->createForm(AvailabilityOverrideType::class, $availabilityOverride);
            $form->submit($request->request->all());

            if (!$form->isValid()) {
                return $this->renderSerializedFormErrors($form);
            }


            $this->save($availabilityOverride);
            $this->availabilityOverrideManager->editAvailabilityOverride($oldOverride, $availabilityOverride);
            $this->commit();

            return $this->renderEmptyResponse();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
    }
}
