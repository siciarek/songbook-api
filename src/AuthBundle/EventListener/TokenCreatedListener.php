<?php

namespace AuthBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * JWTCreatedListener
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class TokenCreatedListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();

        if (!$payload) {
            return;
        }

        /**
         * @var Request $request
         */
        $request = $this->getContainer()->get('request_stack')->getCurrentRequest();
        $payload['ip'] = $request->getClientIp();

        $event->setData($payload);
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
