<?php

namespace Tests\AppBundle;

use Tests\TestCase;
use AppBundle\Entity as E;


/**
 * @group swap
 */
class SwapObjectsTest extends TestCase
{
    public static function swapObjectsDataProvider()
    {
        return [
            [E\Song::class, 'put_song'],
            [E\Artist::class, 'put_artist'],
            [E\Author::class, 'put_author'],
            [E\Video::class, 'put_video'],
            [E\Audio::class, 'put_audio'],
        ];
    }

    /**
     * @dataProvider swapObjectsDataProvider
     */
    public function testPutAction($class, $route)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $router = $this->getContainer()->get('router');

        $repo = $em->getRepository($class);
        $set = $repo->createQueryBuilder('o')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $ids = [];
        do {
            $ids[] = $set[array_rand($set)]->getId();
            $ids = array_unique($ids);
        } while (count($ids) < 2);

        $set = $repo->createQueryBuilder('o')
            ->andWhere('o.id IN (:ids)')
            ->setParameters([
                'ids' => $ids,
            ])
            ->orderBy('o.sort', 'ASC')
            ->getQuery()
            ->getResult();

        $before = [$set[0]->getSort(), $set[1]->getSort()];


        # Swap

        $url = $router->generate($route, array_combine(['item', 'swap'], $ids), $router::ABSOLUTE_URL);
        list($resp, $info) = $this->getResponse('PUT', $url, [], $this->getAuthHeaders());
        $this->assertEquals(204, $info['http_code'], $resp);

        $set = $repo->createQueryBuilder('o')
            ->andWhere('o.id IN (:ids)')
            ->setParameters([
                'ids' => $ids,
            ])
            ->orderBy('o.sort', 'ASC')
            ->getQuery()
            ->getResult();

        $after = [$set[0]->getSort(), $set[1]->getSort()];

        $this->assertNotEquals($before, $after);
        $this->assertEquals($before, array_reverse($after));

        # Revert swap

        $url = $router->generate($route, array_combine(['item', 'swap'], array_reverse($ids)), $router::ABSOLUTE_URL);
        list($resp, $info) = $this->getResponse('PUT', $url, [], $this->getAuthHeaders());
        $this->assertEquals(204, $info['http_code'], $resp);

        $set = $repo->createQueryBuilder('o')
            ->andWhere('o.id IN (:ids)')
            ->setParameters([
                'ids' => $ids,
            ])
            ->orderBy('o.sort', 'ASC')
            ->getQuery()
            ->getResult();

        $after = [$set[0]->getSort(), $set[1]->getSort()];
        $this->assertEquals($before, $after);
    }

    public function getAuthHeaders()
    {
        $router = $this->getContainer()->get('router');
        $authUrl = $router->generate('auth_check', [], $router::ABSOLUTE_URL);
        $authData = [
            'username' => 'colak',
            'password' => 'pass',
        ];
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        list($resp, $info) = $this->getResponse('POST', $authUrl, http_build_query($authData), $headers);

        $data = json_decode($resp, true);

        return [
            sprintf('Authorization: Bearer %s', $data['token']),
        ];
    }

    public function setUp()
    {
        parent::setUp();
    }
}
