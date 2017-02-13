<?php

namespace AppBundle\Controller;

use AppBundle\Security\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @param UserManager $userManager
     */
    public function setUserManager(UserManager $userManager) {
        $this->userManager = $userManager;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');
        return $this->render('Security/Login.html', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/api/login", name="security_login_check")
     */
    public function loginCheckAction()
    {
        // will never be executed
        return new Response();
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // will never be executed
        return new Response();
    }

    /**
     * @Route("/api/reset-password", name="api_forgot_password")
     */
    public function resetPasswordAction(Request $request)
    {
        $payload = json_decode($request->getContent(), TRUE);

        if (!isset($payload['email'])) {
            return JsonResponse::create(['message' => 'missing email.']);
        }

        return JsonResponse::create(['message' => 'ok']);
    }

    /**
     * @Route("/api/login/status", name="api_login_status")
     */
    public function loginStatusAction()
    {
        return new JsonResponse([
            'message' => 'logged in'
        ]);
    }

}
