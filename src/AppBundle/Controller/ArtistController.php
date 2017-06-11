<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("Artist", pluralize=false)
 */
class ArtistController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Artist::class)
            ->createQueryBuilder('o');

        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $this->getParameter('paginator_limit', 10)
        );

        return $paginator->getItems();
    }

    /**
     * Returns data of the artist identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Artist")
     */
    public function getAction(Artist $item)
    {
        return $item;
    }
}