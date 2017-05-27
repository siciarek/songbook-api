<?php

namespace AuthBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        # TODO - wydobyć prawdziwe IP
        $ip = '127.0.0.1';

        if (!($request = $event->getData())) {
            return;
        }

        $payload       = $event->getData();

        $payload['ip'] = $ip;

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
