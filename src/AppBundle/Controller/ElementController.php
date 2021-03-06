<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Element;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RouteResource;

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

        /**
         * Check if id given in url is the same as in data.
         */
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
     * Returns data of the single item.
     *
     * @ParamConverter("item", class="AppBundle:Element")
     */
    public function getAction(Element $item)
    {
        return $item;
    }

    /**
     * Returns list of items.
     */
    public function cgetAction(Request $request)
    {
        /**
         * @var QueryBuilder $qb
         */
        $qb = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository(Element::class)
            ->createQueryBuilder('e')
            ->addOrderBy('e.id', Criteria::DESC);

        /**
         * @var AbstractPagination $paginator
         */
        $paginator = $this->getPager($qb, $request->query->getInt('page', 1));

        return $paginator->getItems();
    }

    /**
     * Unserialize item.
     *
     * @param $content - JSON string
     * @param $class - Class name
     * @return mixed
     */
    protected function unserializeItem($content, $class)
    {
        return $this->get('jms_serializer')
            ->deserialize($content, $class, self::FORMAT_JSON);
    }
}