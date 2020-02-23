<?php
declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class LocaleListener
{
    private UserPreferences $userPreferences;
    private ParameterBagInterface $params;

    public function __construct(UserPreferences $userPreferences, ParameterBagInterface $params)
    {
        $this->userPreferences = $userPreferences;
        $this->params = $params;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $requestLocale = $event->getRequest()->getLocale();
        if ($requestLocale === null) {
            $requestLocale = $this->params->get('default_locale');
        }
        $this->userPreferences->setLocale($requestLocale);
    }
}
