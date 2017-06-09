<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genre;
use AppBundle\Entity\Song;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @RouteResource("Song", pluralize=false)
 */
class SongController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Adds new song.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Adds new song.",
     * )
     */
    public function postAction(Request $request)
    {
        $item = $this
            ->get('jms_serializer')
            ->deserialize($request->getContent(), Song::class, 'json');

        $em = $this->getDoctrine()->getManager();

        # TODO: use jms deserialize more efficiently:
        $genre = $em
            ->getRepository(get_class($item->getGenre()))
            ->find($item->getGenre()->getId());
        $item->setGenre($genre);

        $em->persist($item);
        $em->flush();

        return $item;
    }

    /**
     * Modifies data of song identified by id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Modifies data of song identified by id.",
     * )
     */
    public function putAction(Request $request, $id)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        $em = $this->get('doctrine.orm.entity_manager');
        $item = $em->getRepository(Song::class)->find($id);

        if(!$item instanceof Song) {
            throw $this->createNotFoundException('Invalid id.');
        }

        $fields = [
            'genre' => function($value) use ($item, $em) {
                $value = $em->getRepository(Genre::class)->findOneById($value['id']);
                $item->setGenre($value);
            },
            'title' => function ($value) use ($item) {
                $item->setTitle($value);
            },
            'lyrics' => function ($value) use ($item) {
                $item->setLyrics($value);
            },
        ];

        foreach ($fields as $field => $func) {
            $func($data[$field]);
        }

        $em->persist($item);
        $em->flush();

        return $item;
    }


    /**
     * Removes song identified by id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Removes song identified by id.",
     * )
     * @ParamConverter("item", class="AppBundle:Song")
     */
    public function deleteAction(Song $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
    }
}