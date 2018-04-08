<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Element;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;

/**
 * @RouteResource("Element", pluralize=false)
 */
class ElementController extends RestController implements ClassResourceInterface
{


    /**
     * Update single element.
     *
     * @ParamConverter("item", class="AppBundle:Element")
     */
    public function putAction(Element $item, Request $request)
    {
        /**
         * @var EntityManagerInterface $em
         */
        $em = $this->get('doctrine.orm.entity_manager');
        /**
         * @var SerializerInterface $serializer
         */
        $serializer = $this->get('jms_serializer');
        $updatedItem = $serializer->deserialize($request->getContent(), Element::class, 'json');

        $em->persist($updatedItem);
        $em->flush();

        return $updatedItem;
    }

    /**
     * Create single element.
     */
    public function postAction(Request $request)
    {
        /**
         * @var EntityManagerInterface $em
         */
        $em = $this->get('doctrine.orm.entity_manager');
        /**
         * @var SerializerInterface $serializer
         */
        $serializer = $this->get('jms_serializer');
        $item = $serializer->deserialize($request->getContent(), Element::class, 'json');

        $em->persist($item);
        $em->flush();

        return $item;
    }

    /**
     * Returns data of the element identified by id.
     *
     * @ParamConverter("item", class="AppBundle:Element")
     */
    public function getAction(Element $item)
    {
        return $item;
    }

    public function cgetAction(Request $request)
    {
        /**
         * @var QueryBuilder $qb
         */
        $qb = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository(Element::class)
            ->createQueryBuilder('e')
            ->addOrderBy('e.id', 'DESC');

        $paginator = $this->getPager($qb, $request->query->getInt('page', 1));

        return $paginator->getItems();
    }
}