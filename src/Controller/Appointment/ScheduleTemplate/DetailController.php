<?php

namespace App\Controller\Appointment\ScheduleTemplate;

use App\Controller\Controller;
use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Error\ErrorCodeEnum;
use App\Security\Voter\Appointment\ScheduleTemplate\CanViewScheduleTemplateVoter;
use App\Serializer\Appointment\ScheduleTemplate\ScheduleTemplateGroupsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DetailController extends Controller
{
    #[Route(path: '/schedule-template/{scheduleTemplate}', requirements: ['scheduleTemplate' => '\d+'], methods: ['GET'])]
    #[IsGranted(
        attribute: CanViewScheduleTemplateVoter::CAN_VIEW_SCHEDULE_TEMPLATE,
        subject: 'scheduleTemplate',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function detailScheduleTemplate(ScheduleTemplate $scheduleTemplate): JsonResponse
    {
        return $this->renderSerializedData($scheduleTemplate, ScheduleTemplateGroupsHelper::scheduleTemplate());
    }
}
