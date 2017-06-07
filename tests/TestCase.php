<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestCase extends KernelTestCase implements ContainerAwareInterface {

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    public function setUp() {
        self::bootKernel();
        $this->setContainer(static::$kernel->getContainer());
    }

    public function getResponse($method, $url, $data = [], $headers = null)
    {
        $method = strtoupper($method);
        $ch = curl_init();

        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_VERBOSE, false);

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_PUT, true);
                break;
            case 'DELETE':
                curl_setopt($ch,  CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return [$resp, $info];
    }

    public function getContainer() {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}