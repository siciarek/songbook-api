<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\CommonController as Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 *
 * @Route("/user")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/dashboard", name="user.dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $data = [
            'message' => 'User dashboard',
        ];
        return $this->getJsonResponse($data);
    }

    /**
     * @Route("/profile", name="user.profile")
     */
    public function profileAction(Request $request)
    {
        $data = [
            'message' => 'User profile',
        ];
        return $this->getJsonResponse($data);
    }
}
