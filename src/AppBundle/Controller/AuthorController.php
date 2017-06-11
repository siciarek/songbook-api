<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("Author", pluralize=false)
 */
class AuthorController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Returns page of the list of Authors
     *
     * @param Request $request
     * @return mixed
     */
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Author::class)
            ->createQueryBuilder('o');

        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $this->getParameter('paginator_limit', 10)
        );

        return $paginator->getItems();
    }

    /**
     * Returns data of the author identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Author")
     */
    public function getAction(Author $item)
    {
        return $item;
    }
}