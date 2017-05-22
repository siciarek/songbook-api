<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BasicFixture;
use AppBundle\Entity\GenreCategory;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Yaml\Yaml;
use AppBundle\Entity\Artist;
use AppBundle\Entity\Author;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Audio;
use AppBundle\Entity\Song;
use AppBundle\Entity\Video;

class LoadGenresData extends BasicFixture
{

    /**
     * @var int
     */
    protected $order = 3;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('usersystem');
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $this->getContainer()
            ->get('security.token_storage')
            ->setToken($token);

        $path = __DIR__ . '/../data/genres.json';
        $json = file_get_contents($path);
        $data = json_decode($json, true);


        foreach($data as $category => $genres) {
            $o = new GenreCategory();
            $o->setName($category);
            $manager->persist($o);
            $manager->flush();

            $g = new Genre();
            $g->setEnabled();
            $g->setName($category);
            $g->setCategory($o);
            $manager->persist($g);

            foreach($genres as $genre) {
                $g = new Genre();
                $g->setName($genre);
                $g->setCategory($o);
                $manager->persist($g);
            }

            $manager->flush();
        }
    }
}
