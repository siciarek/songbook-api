<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("Artist", pluralize=false)
 */
class ArtistController extends RestController implements ClassResourceInterface
{
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Artist::class)
            ->createQueryBuilder('o')
            ->addOrderBy('o.sort', 'ASC')
        ;

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

    /**
     * Midifies data of the artist identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Artist")
     */
    public function putAction(Artist $item, Request $request)
    {
        $targetId = (int) $request->get('swap', 0);

        if ($targetId > 0 and $item->getId() !== $targetId) {
            $em = $this->getDoctrine()->getManager();
            $target = $em->getRepository(get_class($item))->find($targetId);

            $from = $item->getSort();
            $to = is_a($target, get_class($item)) ? $target->getSort() : 0;

            if ($to > 0) {
                $item->setSort($to);
                $target->setSort($from);

                $em->persist($item);
                $em->persist($target);
                $em->flush();
            }
        }
    }
}