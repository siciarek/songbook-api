<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Genre;
use JMS\Serializer\EventDispatcher\{
    EventSubscriberInterface, ObjectEvent, PreDeserializeEvent, PreSerializeEvent
};

class GenreEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerialize',
                'class' => Genre::class,
                'format' => 'json',
                'priority' => 0,
            ],
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => Genre::class,
                'format' => 'json',
                'priority' => 1,
            ],
            [
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
                'class' => Genre::class,
                'format' => 'json',
                'priority' => 0,
            ],
            [
                'event' => 'serializer.post_deserialize',
                'method' => 'onPostDeserialize',
                'class' => Genre::class,
                'format' => 'json',
                'priority' => 0,
            ],
        ];
    }

    public function onPreSerialize(PreSerializeEvent $event)
    {
//        dump(__METHOD__, $event); # OK
//        exit;
    }

    public function onPostSerialize(ObjectEvent $event)
    {
//        dump(__METHOD__, $event); # OK
//        exit;
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
//        dump(__METHOD__, $event); # OK
//        exit;
    }

    public function onPostDeserialize(ObjectEvent $event)
    {
//        dump(__METHOD__, $event); # OK
//        exit;
    }
}