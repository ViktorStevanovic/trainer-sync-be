<?php

namespace App\Controller\Appointment\ScheduleTemplate;

use App\Controller\Controller;
use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Error\ErrorCodeEnum;
use App\Security\Voter\Appointment\ScheduleTemplate\CanViewScheduleTemplateVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DeleteController extends Controller
{

    #[Route(path: '/schedule-template/{scheduleTemplate}', requirements: ['scheduleTemplate' => '\d+'], methods: ['DELETE'])]
    #[IsGranted(
        attribute: CanViewScheduleTemplateVoter::CAN_VIEW_SCHEDULE_TEMPLATE,
        subject: 'scheduleTemplate',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function editScheduleTemplate(Request $request, ScheduleTemplate $scheduleTemplate): JsonResponse
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
