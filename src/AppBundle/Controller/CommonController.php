<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class CommonController extends Controller
{
    protected function getJsonResponse($data)
    {

        $json = json_encode($data, JSON_PRETTY_PRINT);

        $headers = [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE,OPTIONS',
            'Access-Control-Max-Age' => '3600',
            'Access-Control-Allow-Headers' => 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
        ];

        sleep(1); # fake performance issue

        return new Response($json, 200, $headers);
    }
}
