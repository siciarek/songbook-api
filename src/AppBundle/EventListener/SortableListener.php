<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Knp\DoctrineBehaviors\Model\Sortable\Sortable;

/**
 * Class SortableListener
 * @package AppBundle\EventListener
 *
 * Usage:
 *
 * Add to your Resources/config/services.yml
 *
 * services:
 *
 *     AppBundle\EventListener\SortableListener:
 *         tags:
 *             - { name: doctrine.event_listener, event: prePersist, connection: default }
 */

class SortableListener
{
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
}