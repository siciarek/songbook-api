<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genre;
use AppBundle\Entity\GenreCategory;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

        $builder = $this->getDoctrine()
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

        return $paginator->getItems();
    }

    /**
     * Returns data of music genre identified by id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns data of music genre identified by id.",
     * )
     * @ParamConverter("genre", class="AppBundle:Genre")
     */
    public function getAction(Genre $genre)
    {
        return $genre;
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
        $em = $this->get('doctrine.orm.entity_manager');
        $serializer = SerializerBuilder::create()->build();

        $json = $request->getContent();
        $item = $serializer->deserialize($json, 'AppBundle\Entity\Genre', 'json');

//        $em->persist($item);
//        $em->flush();
//        return $item;

        $data = json_decode($json, true);

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

        return $item;
    }


    /**
     * Removes music genre identified by id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Removes music genre identified by id.",
     * )
     * @ParamConverter("item", class="AppBundle:Genre")
     */
    public function deleteAction(Genre $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
    }
}