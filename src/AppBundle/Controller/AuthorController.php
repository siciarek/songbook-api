<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("Author", pluralize=false)
 */
class AuthorController extends RestController implements ClassResourceInterface
{
    /**
     * Returns page of the list of Authors
     *
     * @param Request $request
     * @return mixed
     */
    public function cgetAction(Request $request)
    {
        $builder = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Author::class)
            ->createQueryBuilder('o')
            ->addOrderBy('o.sort', 'ASC')
        ;

        $paginator = $this->getPager($builder, $request->query->getInt('page', 1));

        return $paginator->getItems();
    }

    /**
     * Returns data of the author identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Author")
     */
    public function getAction(Author $item)
    {
        return $item;
    }

    /**
     * Modify data of the author identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Author")
     */
    public function putAction(Author $item, Request $request)
    {
        $this->swapObjects($item, $request->get('swap', 0));
    }
}