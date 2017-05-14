<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\GroupManager;
use Symfony\Component\Yaml\Yaml;

class LoadGroupData extends BasicFixture {

    /**
     * @var int
     */
    protected $order = 1;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $path = __DIR__ . '/../data/Group.yml';
        $path = realpath($path);
        $data = Yaml::parse(file_get_contents($path));
        $data = array_pop($data);

        /**
         * @var GroupManager $mngr
         */
        $mngr = $this->getContainer()->get('fos_user.group_manager');

        foreach($data as $o) {
            $group = $mngr->createGroup($o['name']);
            foreach($o['roles'] as $role) {
                $group->addRole($role);
            }
            $mngr->updateGroup($group);
            $this->setReference('group' . $group->getName(), $group);
        }

    }
}
