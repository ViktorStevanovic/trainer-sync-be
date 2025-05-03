<?php

namespace App\Controller\Appointment\ScheduleTemplate;

use App\Controller\Controller;
use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Entity\Trainer\Trainer;
use App\Error\ErrorCodeEnum;
use App\Security\Voter\Appointment\ScheduleTemplate\CanViewScheduleTemplateVoter;
use App\Security\Voter\Appointment\ScheduleTemplate\CanEditScheduleTemplateVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DeleteController extends Controller
{

    #[Route(path: '/trainer/{trainer}/schedule-template/{scheduleTemplate}', requirements: ['trainer' => '\d+', 'scheduleTemplate' => '\d+'], methods: ['DELETE'])]
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
    public function editScheduleTemplate(Request $request, ScheduleTemplate $scheduleTemplate, Trainer $trainer): JsonResponse
    {
        $this->beginTransaction();

        try {
            $this->remove($scheduleTemplate);

            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
        return $this->renderEmptyResponse();
    }
}
