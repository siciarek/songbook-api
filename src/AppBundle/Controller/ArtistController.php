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

        $paginator = $this->getPager($builder, $request->query->getInt('page', 1));

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
        $this->swapObjects($item, $request->get('swap', 0));
    }
}