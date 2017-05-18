<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Song;
use AppBundle\Entity\Video;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @RouteResource("Video", pluralize=false)
 */
class VideoController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->get('doctrine')
            ->getRepository(Song::class)
            ->createQueryBuilder('o')
        ;

        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $this->getParameter('paginator_limit', 10)
        );

        $items = $paginator->getItems();

        $items = array_filter($items, function($e) {
            return $e->getVideos()->count() > 0;
        });

        $items = array_values($items);

        return $items;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAction($id)
    {
        $item = $this
            ->get('doctrine')
            ->getRepository(Song::class)
            ->find($id)
        ;

        return $item->getVideos();
    }
}