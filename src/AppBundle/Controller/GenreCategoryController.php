<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Entity\GenreCategory;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @RouteResource("GenreCategory", pluralize=false)
 */
class GenreCategoryController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(Request $request)
    {
        return $this
            ->getDoctrine()
            ->getRepository(GenreCategory::class)
            ->findBy([], ['name' => 'ASC']);
    }
}