<?php

namespace Tests\AppBundle\Entity;

use appTestDebugProjectContainer;
use AppBundle\Common\Acme;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Tests\TestCase;

/**
 * @group acme
 */
class AcmeTest extends TestCase
{
    /**
     * @var Acme
     */
    protected $srv;

    public static function methodsBasicDataProvider() {
        return [
            ['doTheJob', 'Nazwa aplikacji.'],
            ['doThePartTimeJob', 'Trochę dłuższy opis aplikacji.'],
            ['takeANap', false],
        ];
    }

    /**
     * @dataProvider methodsBasicDataProvider
     */
    public function testMethodsBasic($method, $expected)
    {
        $actual = $this->srv->$method();
        $this->assertEquals($expected, $actual);
    }

    protected function getContainerMock()
    {

        $map = [
            ['app_name', 'Nazwa aplikacji.'],
            ['app_description', 'Trochę dłuższy opis aplikacji.'],
        ];

        # Implements only methods given in $mock->method(), every other method call returns null
        $mock = $this->createMock(Container::class);

        # Overrides methods given in setMethods(), every other method works like in the original class
        $mock = $this->getMockBuilder(Container::class)->setMethods(['getParameter'])->getMock();

        $mock->method('getParameter')->will($this->returnValueMap($map));

        $this->getContainer()->set('@service_container', $mock);

        return $mock;
    }

    public function setUp() {
        $this->srv = $this->getContainer()->get('app.acme');
        $this->srv->setContainer($this->getContainerMock());
    }
}
