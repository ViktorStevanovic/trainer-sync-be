<?php

namespace App\Controller\Appointment\ScheduleTemplate;

use App\Controller\Controller;
use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Entity\Trainer\Trainer;
use App\Error\ErrorCodeEnum;
use App\Form\Appointment\ScheduleTemplate\ScheduleTemplateType;
use App\Security\Voter\Appointment\ScheduleTemplate\CanEditScheduleTemplateVoter;
use App\Security\Voter\Appointment\ScheduleTemplate\CanViewScheduleTemplateVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CreateEditController extends Controller
{
    #[Route(path: '/trainer/{trainer}/schedule-template', requirements: ['trainer' => '\d+'], methods: ['POST'])]
    public function createScheduleTemplate(Request $request, Trainer $trainer): JsonResponse
    {
        $scheduleTemplate = (new ScheduleTemplate())->setTrainer($trainer);

        return $this->manageScheduleTemplate($request, $scheduleTemplate);
    }

    #[Route(path: '/trainer/{trainer}/schedule-template/{scheduleTemplate}', requirements: ['trainer' => '\d+', 'scheduleTemplate' => '\d+'], methods: ['PUT'])]
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
        return $this->manageScheduleTemplate($request, $scheduleTemplate);
    }

    /**
     * @param Request $request
     * @param ScheduleTemplate $scheduleTemplate
     * 
     * @return JsonResponse
     */
    private function manageScheduleTemplate(Request $request, ScheduleTemplate $scheduleTemplate): JsonResponse
    {
        $this->beginTransaction();

        try {
            $form = $this->createForm(ScheduleTemplateType::class, $scheduleTemplate);
            $form->submit($request->request->all());

            if (!$form->isValid()) {
                return $this->renderSerializedFormErrors($form);
            }

            $this->save($scheduleTemplate);
            $this->commit();

            return $this->renderEmptyResponse();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
    }
}
