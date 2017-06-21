<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genre;
use AppBundle\Entity\Song;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;


/**
 * @RouteResource("Song", pluralize=false)
 */
class SongController extends RestController
{
    /**
     * Adds new song.
     *
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
     * @ParamConverter("item", class="AppBundle:Song")
     */
    public function putAction(Song $item, Request $request)
    {

        $json = $request->getContent();
        $data = json_decode($json, true);

        $em = $this->get('doctrine.orm.entity_manager');

        if (!$item instanceof Song) {
            throw $this->createNotFoundException('Invalid id.');
        }

        $this->swapObjects($item, $request->get('swap', 0));

        if ($data !== null) {

            $fields = [
                'genre' => function ($value) use ($item, $em) {
                    $value = $em->getRepository(Genre::class)->findOneById($value['id']);
                    $item->setGenre($value);
                },
                'title' => function ($value) use ($item) {
                    $item->setTitle($value);
                },
                'lyrics' => function ($value) use ($item) {
                    $item->setLyrics($value);
                },
                'firstPublishedAt' => function ($value) use ($item) {
                    $item->setFirstPublishedAt(new \DateTime($value));
                },
            ];

            foreach ($fields as $field => $func) {
                if (array_key_exists($field, $data)) {
                    $func($data[$field]);
                }
            }

            $em->persist($item);
            $em->flush();
        }
    }


    /**
     * Removes song identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Song")
     */
    public function deleteAction(Song $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
    }
}