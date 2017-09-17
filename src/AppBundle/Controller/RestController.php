<?php

namespace AppBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;

abstract class RestController extends FOSRestController
{

    protected function getPager(QueryBuilder $qb, $page = 1) {

        $result = $this->get('knp_paginator')->paginate(
            $qb,
            $page,
            $this->getParameter('paginator_limit', 10)
        );

        return $result;
    }

    /**
     * @param object $item current object
     * @param $targetId id of the object we want to switch with
     * @return object
     */
    public function swapObjects($item, $targetId)
    {
        $targetId = (int) $targetId;

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

        return $item;
    }
}