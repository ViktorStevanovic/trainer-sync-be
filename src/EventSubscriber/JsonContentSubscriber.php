<?php

namespace App\EventSubscriber;

use App\Services\Utils\Helper\JsonConvertContentHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function is_array;

/**
 * Converte il contenuto di una richiesta json.
 */
readonly class JsonContentSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 9]],
        ];
    }

    /**
     * All'arrivo della richiesta verifico che sia tipo JSON e ne converto i dati per essere elaborati.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $request->request = $this->createPostParameterBag($request);
        $request->query = $this->createGetParameterBag($request);
    }

    /**
     * @param Request $request
     * @return ParameterBag
     */
    private function createPostParameterBag(Request $request): ParameterBag
    {
        if ($request->getContent()) {
            $arrayContent = JsonConvertContentHelper::convertJsonContent($request->getContent());
        } else {
            $arrayContent = JsonConvertContentHelper::convertJsonContent($request->request->all());
        }
        if (!is_array($arrayContent)) {
            return $request->request;
        }

        return new ParameterBag($arrayContent);
    }

    /**
     * @param Request $request
     * @return ParameterBag
     */
    private function createGetParameterBag(Request $request): ParameterBag
    {
        return new ParameterBag(JsonConvertContentHelper::convertJsonContent($request->query->all(), false));
    }
}
