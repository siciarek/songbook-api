<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("Dashboard", pluralize=false)
 */
class DashboardController extends FOSRestController implements ClassResourceInterface
{
    public function getAction(Request $request)
    {
        $data = [
            'message' => 'User dashboard',
        ];

        return $data;
    }
}
