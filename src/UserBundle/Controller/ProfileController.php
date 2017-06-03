<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("Profile", pluralize=false)
 */
class ProfileController extends FOSRestController implements ClassResourceInterface
{
    public function getAction(Request $request)
    {
        $data = [
            'message' => 'User profile',
        ];

        return $data;
    }
}
