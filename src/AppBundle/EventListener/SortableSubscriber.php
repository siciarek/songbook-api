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
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if(in_array(Sortable::class, class_uses($entity))) {

            $sort = 1 + (int) $args->getEntityManager()
                ->getRepository(get_class($entity))
                ->createQueryBuilder('o')
                ->select('MAX(o.sort)')
                ->getQuery()
                ->getSingleScalarResult()
            ;

            $entity->setSort($sort);
        }
    }
}