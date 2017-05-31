<?php

namespace AppBundle\Controller;

define('MOCK_HEAVY_LOAD', 0);

use AppBundle\Entity\Genre;
use AppBundle\Entity\GenreCategory;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations\Post;
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
     * Returns a list of available music genres.
     *
     * @QueryParam(name="page", nullable=true, requirements="[1-9]\d*")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a list of available music genres.",
     * )
     */
    public function cgetAction(Request $request)
    {
        $temp = $request->get('enabled', false);
        $temp = mb_convert_case($temp, MB_CASE_LOWER);
        $enabled = in_array($temp, ['1', 'true', 't']);

        $em = $this->get('doctrine.orm.entity_manager');

        $builder = $em
            ->getRepository(Genre::class)
            ->createQueryBuilder('a')
            ->andWhere('a.deletedAt IS NULL')
            ->addOrderBy('a.name', 'ASC');

        if($enabled === true) {
            $builder->andWhere('a.enabled = true');
        }

        $paginator = $this->get('knp_paginator')->paginate(
            $builder,
            $request->get('page', 1),
            $this->getParameter('paginator_limit', 10)
        );

        sleep(MOCK_HEAVY_LOAD);

        return $paginator->getItems();
    }

    /**
     * Returns data of music genre identified by id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns data of music genre identified by id.",
     * )
     */
    public function getAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $item = $em
            ->getRepository(Genre::class)
            ->findOneBy(['id' => $id]);

        if(false === ($item instanceof Genre) or true === $item->isDeleted()) {
            throw $this->createNotFoundException('Invalid id.');
        }

        sleep(MOCK_HEAVY_LOAD);

        return $item;
    }

    /**
     * Creates new music genre.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Creates new music genre.",
     * )
     */
    public function postAction(Request $request)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        $em = $this->get('doctrine.orm.entity_manager');

        $item = new Genre();
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

        foreach($data as $key => $value) {
            $fields[$key]($value);
        }

        $em->persist($item);
        $em->flush();

        sleep(MOCK_HEAVY_LOAD);

        return $item;
    }

    /**
     * Modifies data of music genre identified by id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Modifies data of music genre identified by id.",
     * )
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

        if(!$item instanceof Genre) {
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

        sleep(MOCK_HEAVY_LOAD);

        return $item;
    }


    /**
     * Removes music genre identified by id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Removes music genre identified by id.",
     * )
     */
    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $item = $em->getRepository(Genre::class)->find($id);

        $em->remove($item);
        $em->flush();

        sleep(MOCK_HEAVY_LOAD);
    }
}