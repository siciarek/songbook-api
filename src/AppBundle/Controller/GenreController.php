<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genre;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @RouteResource("Genre", pluralize=false)
 */
class GenreController extends FOSRestController implements ClassResourceInterface
{
    /**
     * List of genres.
     *
     * @QueryParam(name="page", nullable=true, requirements="[1-9]\d*")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of Genres",
     * )
     */
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->get('doctrine')
            ->getRepository(Genre::class)
            ->createQueryBuilder('a');

        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $this->getParameter('paginator_limit', 10)
        );

        return $paginator->getItems();
    }

    /**
     * Returns specific genre data.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns specific Genre data.",
     * )
     */
    public function getAction($id)
    {
        $item = $this
            ->get('doctrine')
            ->getRepository(Genre::class)
            ->findOneById($id);

        return $item;
    }
}