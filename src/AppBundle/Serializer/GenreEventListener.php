<?php

namespace AppBundle\Serializer;

use JMS\Serializer\EventDispatcher\{
    ObjectEvent, PreDeserializeEvent, PreSerializeEvent
};

class GenreEventListener
{
    public function onPreSerialize(PreSerializeEvent $event)
    {
        dump(__METHOD__, $event); # OK
        exit;
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        dump(__METHOD__, $event); # OK
        exit;
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        dump(__METHOD__, $event); # OK
        exit;
    }

    public function onPostDeserialize(ObjectEvent $event)
    {
        dump(__METHOD__, $event); # OK
        exit;
    }
}