<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Item;
use Doctrine\ORM\EntityManager;
use Tests\TestCase;

/**
 * @group item
 */
class ItemTest extends TestCase
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param $count
     * @param $username
     */
    public function testSortableCreate($count = 20, $username = 'system')
    {
        foreach ($this->em->getRepository(Item::class)->findAll() as $item) {
            $this->em->remove($item);
        }
        $this->em->flush();

        $this->logInUser($username);

        foreach (range(1, $count) as $i) {
            $item = new Item();
            $item->setName('Item #' . $i);
            $this->em->persist($item);
            $this->em->flush();
        }

        $items = $this->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository(Item::class)->findAll();

        $this->assertCount($count, $items);

        return $items;
    }

    /**
     * @depends testSortableCreate
     * @param $items
     */
    public function testSortableDelete($items) {

        $before = count($items);

        foreach(range(1, $before) as $i) {
            $item = $items[array_rand($items)];

            $item = $this->getContainer()->get('doctrine.orm.entity_manager')
                ->getRepository(Item::class)->find($item->getId());
            $this->em->remove($item);
            $this->em->flush();

            $items = $this->getContainer()->get('doctrine.orm.entity_manager')
                ->getRepository(Item::class)->findAll();

            $this->assertCount($before - $i, $items);
        }
    }

    public function setUp()
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
