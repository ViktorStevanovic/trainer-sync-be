<?php

namespace App\Controller\Appointment\ScheduleTemplate;

use App\Controller\Controller;
use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Entity\Trainer\Trainer;
use App\Error\ErrorCodeEnum;
use App\Security\Voter\Appointment\ScheduleTemplate\CanViewScheduleTemplateVoter;
use App\Security\Voter\Appointment\ScheduleTemplate\CanEditScheduleTemplateVoter;
use App\Serializer\Appointment\ScheduleTemplate\ScheduleTemplateGroupsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DetailController extends Controller
{
    #[Route(path: '/trainer/{trainer}/schedule-template/{scheduleTemplate}', requirements: ['trainer' => '\d+', 'scheduleTemplate' => '\d+'], methods: ['GET'])]
    #[IsGranted(
        attribute: CanEditScheduleTemplateVoter::CAN_EDIT_SCHEDULE_TEMPLATE,
        subject: ['scheduleTemplate', 'trainer'],
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    #[IsGranted(
        attribute: CanViewScheduleTemplateVoter::CAN_VIEW_TRAINER_SCHEDULE_TEMPLATES,
        subject: 'trainer',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function detailScheduleTemplate(Trainer $trainer, ScheduleTemplate $scheduleTemplate): JsonResponse
    {
        return $this->renderSerializedData($scheduleTemplate, ScheduleTemplateGroupsHelper::scheduleTemplate());
    }
}
