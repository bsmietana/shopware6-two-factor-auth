<?php

declare(strict_types=1);

namespace RuneLaenen\TwoFactorAuth\Controller;

use RuneLaenen\TwoFactorAuth\Event\StorefrontTwoFactorAuthEvent;
use RuneLaenen\TwoFactorAuth\Event\StorefrontTwoFactorCancelEvent;
use RuneLaenen\TwoFactorAuth\Service\TimebasedOneTimePasswordServiceInterface;
use Shopware\Core\Checkout\Customer\SalesChannel\AbstractLogoutRoute;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class StorefrontTwoFactorAuthController extends StorefrontController
{
    public function __construct(
        private readonly TimebasedOneTimePasswordServiceInterface $totpService,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly AbstractLogoutRoute $logoutRoute,
    ) {}

    #[Route(path: '/rl-2fa/verification', name: 'frontend.rl2fa.verification', methods: ['GET', 'POST'])]
    public function verification(Request $request, SalesChannelContext $context): Response
    {
        $customer = $context->getCustomer();

        if (!$customer || !$customer->getCustomFields() || empty($customer->getCustomFields()['rl_2fa_secret'])) {
            return $this->redirectToRoute('frontend.account.login.page', $request->query->all());
        }

        if ($request->getMethod() === Request::METHOD_POST) {
            $code = $request->get('otpCode');

            if ($this->totpService->verifyCode($customer->getCustomFields()['rl_2fa_secret'], $code)) {
                $this->dispatcher->dispatch(new StorefrontTwoFactorAuthEvent($context));

                return $this->redirectToRoute('frontend.account.home.page', $request->query->all());
            }

            $this->addFlash('danger', $this->trans('rl-2fa.account.error.incorrect-code'));
        }

        return $this->render('@RuneLaenenTwoFactorAuth/storefront/page/2fa/verification.html.twig');
    }

    #[Route(path: '/rl-2fa/verification/cancel', name: 'frontend.rl2fa.verification.cancel', methods: ['GET'])]
    public function cancelVerification(SalesChannelContext $context, RequestDataBag $dataBag): RedirectResponse
    {
        if ($context->getCustomer() !== null) {
            $this->logoutRoute->logout($context, $dataBag);
        }

        $this->dispatcher->dispatch(new StorefrontTwoFactorCancelEvent($context));

        return $this->redirectToRoute('frontend.account.login.page');
    }
}
