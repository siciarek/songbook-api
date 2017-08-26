<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genre;
use AppBundle\Entity\GenreCategory;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;


/**
 * @RouteResource("Genre", pluralize=false)
 */
class GenreController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Returns a list of available music genres.
     *
     * @QueryParam(name="page", nullable=true, requirements="[1-9]\d*")
     */
    public function cgetAction(Request $request)
    {
        $temp = $request->get('enabled', false);
        $temp = mb_convert_case($temp, MB_CASE_LOWER);
        $enabled = in_array($temp, ['1', 'true', 't']);

        $builder = $this->getDoctrine()
            ->getRepository(Genre::class)
            ->createQueryBuilder('a')
            ->andWhere('a.deletedAt IS NULL')
            ->addOrderBy('a.name', 'ASC');

        if ($enabled === true) {
            $builder->andWhere('a.enabled = true');
        }

        $paginatorLimit = $this->getParameter('paginator_limit', 10);

        /**
         * @var SlidingPagination $paginator
         */
        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $paginatorLimit
        );

        $totalItemCount = $paginator->getTotalItemCount();
        $totalPages = ceil($totalItemCount / $paginatorLimit);
        $metaData = json_encode(['totalItemCount' => $totalItemCount, 'totalPages' => $totalPages]);

        header("X-Meta-Data: {$metaData}");

        return $paginator->getItems();
    }

    /**
     * Returns data of music genre identified by id.
     *
     * @ParamConverter("genre", class="AppBundle:Genre")
     */
    public function getAction(Genre $genre)
    {
        return $genre;
    }

    /**
     * Creates new music genre.
     *
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $item = $this
            ->get('jms_serializer')
            ->deserialize($request->getContent(), Genre::class, 'json');

        // TODO: use jms deserialize more efficiently:
        $category = $em
            ->getRepository(get_class($item->getCategory()))
            ->find($item->getCategory()->getId());
        $item->setCategory($category);

        $em->persist($item);
        $em->flush();

        return $item;
    }

    /**
     * Modifies data of music genre identified by id.
     *
     */
    public function putAction(Request $request, $id)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        /**
         * @var EntityManager $em
         */
        $em = $this->get('doctrine.orm.entity_manager');
        $item = $em->getRepository(Genre::class)->find($id);

        if (!$item instanceof Genre) {
            throw $this->createNotFoundException('Invalid id.');
        }

        $fields = [
            'name' => function ($value) use ($item) {
                $item->setName($value);
            },
            'description' => function ($value) use ($item) {
                $item->setDescription($value);
            },
            'info' => function ($value) use ($item) {
                $item->setInfo($value);
            },
            'category' => function ($value) use ($item, $em) {
                $category = $em->getRepository(GenreCategory::class)->find($value['id']);
                $item->setCategory($category);
            },
        ];

        unset($data['id']);

        foreach ($fields as $field => $func) {
            $func($data[$field]);
        }

        $em->persist($item);
        $em->flush();

        return $item;
    }


    /**
     * Removes music genre identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Genre")
     */
    public function deleteAction(Genre $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
    }
}
