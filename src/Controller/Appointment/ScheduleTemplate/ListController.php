<?php

namespace App\Controller\Appointment\ScheduleTemplate;

use App\Controller\Controller;
use App\Entity\Trainer\Trainer;
use App\Error\ErrorCodeEnum;
use App\Form\Appointment\ScheduleTemplate\ScheduleTemplateFilterType;
use App\Model\Form\Appointment\ScheduleTemplate\ScheduleTemplateFilter;
use App\Security\Voter\Appointment\ScheduleTemplate\CanViewScheduleTemplateVoter;
use App\Serializer\Appointment\ScheduleTemplate\ScheduleTemplateGroupsHelper;
use App\Services\Appointment\ScheduleTemplate\ScheduleTemplateLister;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListController extends Controller
{
    public function __construct(
        private readonly ScheduleTemplateLister $scheduleTemplateLister
    ) {}

    #[Route(path: '/trainer/{trainer}/schedule-template', requirements: ['trainer' => '\d+'], methods: ['GET'])]
    #[IsGranted(
        attribute: CanViewScheduleTemplateVoter::CAN_VIEW_TRAINER_SCHEDULE_TEMPLATES,
        subject: 'trainer',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function listTrainersScheduleTemplates(Request $request, Trainer $trainer): JsonResponse
    {
        $filter = new ScheduleTemplateFilter()
            ->setTrainer($trainer);
        $form = $this->createForm(ScheduleTemplateFilterType::class, $filter);

        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->renderSerializedFormErrors($form);
        }

        $scheduleTemplates = $this->scheduleTemplateLister->getTrainersScheduleTemplates(filter: $filter);
        return $this->renderSerializedData($scheduleTemplates, ScheduleTemplateGroupsHelper::scheduleTemplate());
    }
}
