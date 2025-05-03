<?php

namespace App\Services\Appointment\ScheduleTemplate;

use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Entity\Trainer\Trainer;
use App\Model\Form\Appointment\ScheduleTemplate\ScheduleTemplateFilter;
use App\Repository\Appointment\ScheduleTemplate\ScheduleTemplateRepository;
use App\Services\Utils\Helper\DoctrineHelper;
use App\Services\Utils\Helper\LoggedUserService;

readonly class ScheduleTemplateLister
{

    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserService $loggedUserService
    ) {}

    /**
     * @param ScheduleTemplateFilter $filter
     * 
     * @return ScheduleTemplate[]
     */
    public function getTrainersScheduleTemplates(ScheduleTemplateFilter $filter): array
    {
        /** @var ScheduleTemplateRepository $repo */
        $repo = $this->doctrineHelper->getRepository(ScheduleTemplate::class);

        $qb = $repo->createBaseVisibleQb(trainer: $filter->getTrainer());

        $weekDays = $filter->getWeekDays();
        if (!empty($weekDays)) {
            $qb
                ->andWhere('st.weekDay in (:weekDays)')
                ->setParameter('weekDays', $weekDays);
        }

        return $qb->getQuery()->getResult();
    }
}
