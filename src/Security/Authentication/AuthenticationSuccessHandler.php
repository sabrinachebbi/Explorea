<?php
namespace App\Security\Authentication;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        protected AuthorizationCheckerInterface $authorizationChecker,
        protected RouterInterface $router
    ) {}
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();

        switch (true) {
            case $this->authorizationChecker->isGranted('ROLE_ADMIN', $user):
                $route = 'admin_dashboard';
                break;
            case $this->authorizationChecker->isGranted('ROLE_HOST', $user):
                $route = 'host_dashboard';
                break;
            default:
                $route = 'home';
        }

        return new RedirectResponse($this->router->generate($route));
    }
}