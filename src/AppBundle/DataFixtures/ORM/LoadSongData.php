<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BasicFixture;
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

class LoadSongData extends BasicFixture
{

    /**
     * @var int
     */
    protected $order = 4;

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

        $path = __DIR__ . '/../data/songs.php';
        $data = require_once $path;

        $genres = [];
        $authors = [];
        $artists = [];
        $audio = [];
        $videos = [];

        $aux = 1;
        $arx = 1;

        foreach ($data as $o) {


            if (!array_key_exists($o['genre'], $genres)) {

                $g = $manager->getRepository(Genre::class)->find($o['genre']);
                $genres[$o['genre']] = $g;
            }

            $genre = $genres[$o['genre']];

            $s = new Song();
            $s->setGenre($genre);
            $s->setTitle($o['title']);
            $s->setLyrics($o['lyrics']);

            $manager->persist($s);
            $manager->flush();

            foreach ($o['authors'] as $oa) {
                if (!array_key_exists($oa['id'], $authors)) {
                    $a = new Author();
                    if (isset($oa['firstName'])) {
                        $a->setFirstName($oa['firstName']);
                    }
                    if (isset($oa['name'])) {
                        $a->setFirstName($oa['name']);
                    }
                    if (isset($oa['lastName'])) {
                        $a->setLastName($oa['lastName']);
                    }
                    $a->setDescription($oa['description']);
                    if ($a->getName() === null) {
//                        $a->setName($oa['description']);
                    }
                    $a->setInfo($oa['info']);
                    $a->setSort($aux++);

                    $manager->persist($a);
                    $manager->flush();

                    $authors[$oa['id']] = $a;
                }

                $s->addAuthor($authors[$oa['id']]);
            }

            foreach ($o['artists'] as $oa) {
                if (!array_key_exists($oa['id'], $artists)) {
                    $a = new Artist();

                    if (isset($oa['firstName'])) {
                        $a->setFirstName($oa['firstName']);
                    }
                    if (isset($oa['name'])) {
                        $a->setFirstName($oa['firstNname']);
                    }
                    if (isset($oa['lastName'])) {
                        $a->setLastName($oa['lastName']);
                    }
                    $a->setDescription($oa['description']);
                    if ($a->getName() === null) {
                        $a->setName($oa['description']);
                    }
                    $a->setInfo($oa['info']);
                    $a->setSort($arx++);

                    $manager->persist($a);
                    $manager->flush();

                    $artists[$oa['id']] = $a;
                }

                $s->addArtist($artists[$oa['id']]);
            }

            foreach ($o['videos'] as $i) {
                if (!array_key_exists($i['id'], $videos)) {
                    $v = new Video();

                    $v->setSource($i['source']);
                    $v->setPath($i['url']);
                    $v->setDescription($i['info']);

                    foreach ($i['artists'] as $oa) {
                        if (!array_key_exists($oa['id'], $artists)) {
                            $a = new Artist();

                            if (isset($oa['firstName'])) {
                                $a->setFirstName($oa['firstName']);
                            }
                            if (isset($oa['name'])) {
                                $a->setFirstName($oa['name']);
                            }
                            if (isset($oa['lastName'])) {
                                $a->setLastName($oa['lastName']);
                            }
                            $a->setDescription($oa['description']);
                            if ($a->getName() === null) {
                                $a->setName($oa['description']);
                            }
                            $a->setInfo($oa['info']);
                            $a->setSort($arx++);

                            $manager->persist($a);
                            $manager->flush();

                            $artists[$oa['id']] = $a;
                        }
                        $v->addArtist($artists[$oa['id']]);
                    }

                    $manager->persist($v);
                    $manager->flush();

                    $videos[$i['id']] = $v;
                }

                $s->addVideo($videos[$i['id']]);
            }

            $manager->persist($s);
            $manager->flush();

            if (isset($o['audio']))
                foreach ($o['audio'] as $i) {
                    if (!array_key_exists($i['id'], $audio)) {
                        $v = new Audio();

                        $v->setSource($i['source']);
                        $v->setPath($i['url']);
                        $v->setDescription($i['info']);

                        foreach ($i['artists'] as $oa) {
                            if (!array_key_exists($oa['id'], $artists)) {
                                $a = new Artist();

                                if (isset($oa['firstName'])) {
                                    $a->setFirstName($oa['firstName']);
                                }
                                if (isset($oa['name'])) {
                                    $a->setFirstName($oa['name']);
                                }
                                if (isset($oa['lastName'])) {
                                    $a->setLastName($oa['lastName']);
                                }
                                $a->setDescription($oa['description']);
                                if ($a->getName() === null) {
                                    $a->setName($oa['description']);
                                }
                                $a->setInfo($oa['info']);

                                $manager->persist($a);
                                $manager->flush();

                                $artists[$oa['id']] = $a;
                            }
                            $v->addArtist($artists[$oa['id']]);
                        }

                        $manager->persist($v);
                        $manager->flush();

                        $audio[$i['id']] = $v;
                    }

                    $s->addAudio($audio[$i['id']]);
                }

            $manager->persist($s);
            $manager->flush();
        }
    }
}
