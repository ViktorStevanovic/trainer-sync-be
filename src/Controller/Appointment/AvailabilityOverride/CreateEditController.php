<?php

namespace App\Controller\Appointment\AvailabilityOverride;

use App\Controller\Controller;
use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Form\Appointment\AvailabilityOverride\AvailabilityOverrideType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateEditController extends Controller
{
    #[Route(path: '/availability-override', methods: ['POST'])]
    public function createAvailabilityOverride(Request $request): JsonResponse
    {
        $availabilityOverride = new AvailabilityOverride()->setTrainer($this->getUser()->getTrainer());
        return $this->manageAvailabilityOverride($request, $availabilityOverride);
    }

    #[Route(path: '/availability-override/{availabilityOverride}', requirements: ['availabilityOverride' => '\d+'], methods: ['PUT'])]
    // #[IsGranted(
    //     attribute: CanViewAvailabilityOverrideVoter::CAN_VIEW_SCHEDULE_TEMPLATE,
    //     subject: 'availabilityOverride',
    //     message: ErrorCodeEnum::ERROR_ENTITY_001
    // )]
    public function editAvailabilityOverride(Request $request, AvailabilityOverride $availabilityOverride): JsonResponse
    {
        return $this->manageAvailabilityOverride($request, $availabilityOverride);
    }

    /**
     * @param Request $request
     * @param AvailabilityOverride $availabilityOverride
     * 
     * @return JsonResponse
     */
    private function manageAvailabilityOverride(Request $request, AvailabilityOverride $availabilityOverride): JsonResponse
    {
        $this->beginTransaction();

        try {
            $form = $this->createForm(AvailabilityOverrideType::class, $availabilityOverride);
            $form->submit($request->request->all());

            if (!$form->isValid()) {
                return $this->renderSerializedFormErrors($form);
            }

            $this->save($availabilityOverride);
            $this->commit();

            return $this->renderEmptyResponse();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
    }
}
