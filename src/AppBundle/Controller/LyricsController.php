<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Entity\Song;

/**
 * @RouteResource("Lyrics", pluralize=false)
 */
class LyricsController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->get('doctrine')
            ->getRepository(Song::class)
            ->createQueryBuilder('o')
            ->andWhere('o.deletedAt is NULL')
        ;

        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $this->getParameter('paginator_limit', 10)
        );

        return $paginator->getItems();
    }

    /**
     * Returns data of the song identified by id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns data of the song identified by id.",
     * )
     * @ParamConverter("item", class="AppBundle:Song")
     */
    public function getAction(Song $item)
    {
        return $item;
    }
}