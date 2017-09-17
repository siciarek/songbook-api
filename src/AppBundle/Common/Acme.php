<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 04.09.17
 * Time: 11:06
 */

namespace AppBundle\Common;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Acme implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function doTheJob()
    {
        return $this->getContainer()->getParameter('app_name');
    }

    public function doThePartTimeJob()
    {
        return $this->getContainer()->getParameter('app_description');
    }

    public function takeANap()
    {
        return $this->getContainer()->hasParameter('app_email');
    }

    /**
     * Returns the container.
     *
     * @return ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}