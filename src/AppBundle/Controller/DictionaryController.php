<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;


/**
 * @RouteResource("Dictionary", pluralize=false)
 */
class DictionaryController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function cgetAction(Request $request)
    {
        $dictionaries = [
            'gender' => [
                [
                    'name' => 'Unknown',
                    'value' => 'unknown',
                ],
                [
                    'name' => 'Female',
                    'value' => 'female',
                ],
                [
                    'name' => 'Male',
                    'value' => 'male',
                ],
            ],
        ];

        $name = $request->get('name');

        return array_key_exists($name, $dictionaries) ? $dictionaries[$name] : [];
    }
}