<?php

namespace AuthBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * JWTDecodedListener
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class TokenDecodedListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     */
    public function onDecoded(JWTDecodedEvent $event)
    {
        $payload = $event->getPayload();

        /**
         * @var Request $request
         */
        $request = $this->getContainer()->get('request_stack')->getCurrentRequest();

        if (!isset($payload['ip']) || $payload['ip'] !== $request->getClientIp()) {
            $event->markAsInvalid();
        }
    }

    public function __construct(ContainerInterface $container = null)
    {
        $this->setContainer($container);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
