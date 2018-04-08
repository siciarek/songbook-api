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
    const FORMAT_JSON = 'json';

    /**
     * Remove single element.
     *
     * @ParamConverter("item", class="AppBundle:Element")
     */
    public function deleteAction(Element $item)
    {
        /**
         * @var EntityManagerInterface $em
         */
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($item);
        $em->flush();
    }

    /**
     * Update single element.
     *
     * @ParamConverter("item", class="AppBundle:Element")
     */
    public function putAction(Element $item, Request $request)
    {
        $id = $item->getId();

        $item = $this->unserializeItem($request->getContent(), Element::class);

        if($id !== $item->getId()) {
            throw $this->createNotFoundException('Invalid input data.');
        }

        /**
         * @var EntityManagerInterface $em
         */
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($item);
        $em->flush();

        return $item;
    }

    /**
     * Create single element.
     */
    public function postAction(Request $request)
    {
        $item = $this->unserializeItem($request->getContent(), Element::class);

        /**
         * @var EntityManagerInterface $em
         */
        $em = $this->get('doctrine.orm.entity_manager');
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

    /**
     * Unserialize item.
     *
     * @param $content
     * @param $class
     * @return mixed
     */
    protected function unserializeItem($content, $class)
    {
        return $this->get('jms_serializer')
            ->deserialize($content, $class, self::FORMAT_JSON);
    }
}