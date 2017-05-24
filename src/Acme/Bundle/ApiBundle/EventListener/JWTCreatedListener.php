<?php

namespace Acme\Bundle\ApiBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

/**
 * JWTCreatedListener
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class JWTCreatedListener
{
    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        # TODO - wydobyć prawdziwe IP
        $ip = '127.0.0.1';

        if (!($request = $event->getData())) {
            return;
        }

        $payload       = $event->getData();

        $payload['ip'] = $ip;

        $event->setData($payload);
    }
}
