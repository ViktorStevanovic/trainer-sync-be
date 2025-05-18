<?php

namespace App\Controller\Appointment\ScheduleTemplate;

use App\Controller\Controller;
use App\Form\Appointment\ScheduleTemplate\ScheduleTemplateFilterType;
use App\Model\Form\Appointment\ScheduleTemplate\ScheduleTemplateFilter;
use App\Serializer\Appointment\ScheduleTemplate\ScheduleTemplateGroupsHelper;
use App\Services\Appointment\ScheduleTemplate\ScheduleTemplateLister;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends Controller
{
    public function __construct(
        private readonly ScheduleTemplateLister $scheduleTemplateLister
    ) {}

    #[Route(path: '/schedule-template', methods: ['GET'])]
    public function listTrainersScheduleTemplates(Request $request): JsonResponse
    {
        $filter = new ScheduleTemplateFilter();
        $form = $this->createForm(ScheduleTemplateFilterType::class, $filter);

        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->renderSerializedFormErrors($form);
        }

        $scheduleTemplates = $this->scheduleTemplateLister->getTrainersScheduleTemplates(filter: $filter);
        return $this->renderSerializedData($scheduleTemplates, ScheduleTemplateGroupsHelper::scheduleTemplate());
    }
}
