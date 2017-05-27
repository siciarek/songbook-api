<?php

namespace AuthBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * JWTResponseListener
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class TokenResponseListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Add public data to the authentication response
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] = array(
            'username' => $user->getUsername(),
            'roles'    => $user->getRoles()
        );

        $event->setData($data);
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
