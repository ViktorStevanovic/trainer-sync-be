<?php

namespace App\Controller\Appointment\AvailabilityOverride;

use App\Controller\Controller;
use App\Form\Appointment\AvailabilityOverride\AvailabilityOverrideFilterType;
use App\Model\Form\Appointment\AvailabilityOverride\AvailabilityOverrideFilter;
use App\Serializer\Appointment\AvailabilityOverride\AvailabilityOverrideGroupsHelper;
use App\Services\Appointment\AvailabilityOverride\AvailabilityOverrideLister;
use App\Services\Appointment\AvailabilitySlot\AvailabilitySlotLister;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends Controller
{
    public function __construct(
        private readonly AvailabilityOverrideLister $overrideLister,
        private readonly AvailabilitySlotLister $availabilitySlotLister
    ) {}

    #[Route(path: '/availability-override', methods: ['GET'])]
    public function listTrainersAvailabilityOverrides(Request $request): JsonResponse
    {
        $filter = new AvailabilityOverrideFilter();
        $form = $this->createForm(AvailabilityOverrideFilterType::class, $filter);

        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->renderSerializedFormErrors($form);
        }

        $availabilityOverrides = $this->overrideLister->getVisibleAvailabilityOverrides(filter: $filter);
        return $this->renderSerializedData($availabilityOverrides, AvailabilityOverrideGroupsHelper::availabilityOverride());
    }
}
