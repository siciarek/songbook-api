<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Song;
use AppBundle\Entity\Video;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;


/**
 * @RouteResource("Video", pluralize=false)
 */
class VideoController extends RestController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->get('doctrine')
            ->getRepository(Song::class)
            ->createQueryBuilder('o')
            ->addOrderBy('o.sort', 'ASC')
        ;

        $paginator = $this->getPager($builder, $request->query->getInt('page', 1));

        $items = $paginator->getItems();

        $items = array_filter($items, function($e) {
            return $e->getVideos()->count() > 0;
        });

        $items = array_values($items);

        return $items;
    }

    /**
     * Returns data of the song identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Song")
     */
    public function getAction(Song $item)
    {
        return $item->getVideos();
    }

    /**
     * Midifies data of the artist identified by id.
     * @ParamConverter("item", class="AppBundle:Video")
     * @QueryParam(key="swap", requirements="[1-9]\d+", nullable=true)
     */
    public function putAction(Video $item, Request $request)
    {
        $this->swapObjects($item, $request->get('swap', 0));
    }
}