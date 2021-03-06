<?php

namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Knp\DoctrineBehaviors\Model\Sortable\Sortable;

class SortableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preRemove',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (in_array(Sortable::class, class_uses($entity))) {

            $sort = 1 + (int)$args->getEntityManager()
                    ->getRepository(get_class($entity))
                    ->createQueryBuilder('o')
                    ->select('MAX(o.sort)')
                    ->getQuery()
                    ->getSingleScalarResult();

            $entity->setSort($sort);
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (in_array(Sortable::class, class_uses($entity))) {

            $sort = $entity->getSort();

            $items = $args->getEntityManager()
                ->getRepository(get_class($entity))
                ->createQueryBuilder('o')
                ->andWhere('o.sort > :sort')
                ->getQuery()
                ->execute(['sort' => $sort]);

            /**
             * @var Sortable $item
             */
            foreach ($items as $item) {
                $newSort = (int) $item->getSort() - 1;
                $item->setSort($newSort);
                $args->getEntityManager()->persist($item);
            }
        }
    }
}