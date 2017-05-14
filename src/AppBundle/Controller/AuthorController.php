<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @RouteResource("Author", pluralize=false)
 */
class AuthorController extends FOSRestController implements ClassResourceInterface
{
    /**
     * List of authors.
     *
     * @QueryParam(name="page", nullable=true, requirements="[1-9]\d*")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of Authors",
     * )
     */
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->get('doctrine')
            ->getRepository(Author::class)
            ->createQueryBuilder('a');

        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $this->getParameter('paginator_limit', 10)
        );

        return $paginator->getItems();
    }

    /**
     * Returns specific author data.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns specific Author data.",
     * )
     */
    public function getAction($id)
    {
        $item = $this
            ->get('doctrine')
            ->getRepository(Author::class)
            ->findOneById($id);

        return $item;
    }
}