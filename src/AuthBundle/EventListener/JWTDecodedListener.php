<?php

namespace AuthBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;

/**
 * JWTDecodedListener
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class JWTDecodedListener
{
    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {
        if (!($request = $event->getRequest())) {
            return;
        }

        $payload = $event->getPayload();
        $request = $event->getRequest();

        if (!isset($payload['ip']) || $payload['ip'] !== $request->getClientIp()) {
            $event->markAsInvalid();
        }
    }
}
