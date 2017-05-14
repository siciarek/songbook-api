<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @RouteResource("Artist", pluralize=false)
 */
class ArtistController extends FOSRestController implements ClassResourceInterface
{
    /**
     * List of artists.
     *
     * @QueryParam(name="page", nullable=true, requirements="[1-9]\d*")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of Artists",
     * )
     */
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->get('doctrine')
            ->getRepository(Artist::class)
            ->createQueryBuilder('a');

        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $this->getParameter('paginator_limit', 10)
        );

        return $paginator->getItems();
    }

    /**
     * Returns specific artist data.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns specific Artist data.",
     * )
     */
    public function getAction($id)
    {
        $item = $this
            ->get('doctrine')
            ->getRepository(Artist::class)
            ->findOneById($id);

        return $item;
    }
}