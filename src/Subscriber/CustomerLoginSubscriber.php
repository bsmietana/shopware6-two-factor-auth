<?php

declare(strict_types=1);

namespace RuneLaenen\TwoFactorAuth\Subscriber;

use RuneLaenen\TwoFactorAuth\Event\StorefrontTwoFactorAuthEvent;
use RuneLaenen\TwoFactorAuth\Event\StorefrontTwoFactorCancelEvent;
use Shopware\Core\Checkout\Customer\Event\CustomerLoginEvent;
use Shopware\Core\SalesChannelRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

readonly class CustomerLoginSubscriber implements EventSubscriberInterface
{
    public const string SESSION_NAME = 'RL_2FA_NEED_VERIFICATION';

    public function __construct(private RequestStack $requestStack, private RouterInterface $router) {}

    public static function getSubscribedEvents(): array
    {
        return [
            CustomerLoginEvent::class => 'onCustomerLoginEvent',
            KernelEvents::CONTROLLER => 'onController',
            StorefrontTwoFactorAuthEvent::class => 'removeSession',
            StorefrontTwoFactorCancelEvent::class => 'removeSession',
        ];
    }

    public function onController(ControllerEvent $event): void
    {
        if (!$this->requestStack->getSession()->has(self::SESSION_NAME)) {
            return;
        }

        if (!$event->isMainRequest()) {
            return;
        }

        if ($event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        if (!$event->getRequest()->attributes->get(SalesChannelRequest::ATTRIBUTE_IS_SALES_CHANNEL_REQUEST)) {
            return;
        }

        if (\in_array($event->getRequest()->get('_route'), [
            'frontend.rl2fa.verification',
            'frontend.rl2fa.verification.cancel',
        ], true)) {
            return;
        }

        $queries = $event->getRequest()->query;
        $parameters = [];

        if ($queries->has('redirectTo')) {
            $parameters['redirect'] = $queries->all();
        }

        $url = $this->router->generate('frontend.rl2fa.verification', $parameters);

        $response = new RedirectResponse($url);

        $response->send();
    }

    public function onCustomerLoginEvent(CustomerLoginEvent $event): void
    {
        if (!$event->getCustomer()
            || !$event->getCustomer()->getCustomFields()
            || empty($event->getCustomer()->getCustomFields()['rl_2fa_secret'])
        ) {
            return;
        }

        $this->requestStack->getSession()->set(self::SESSION_NAME, true);
    }

    public function removeSession(): void
    {
        $this->requestStack->getSession()->remove(self::SESSION_NAME);
    }
}
