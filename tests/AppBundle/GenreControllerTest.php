<?php

namespace Tests\AuthBundle;

use AppBundle\Entity\Genre;
use Tests\TestCase;

/**
 * @group genre
 */
class GenreControllerTest extends TestCase
{
    const DUMMY_GENRE_NAME = 'Dummy Genre';

    public static function postActionDataProvider()
    {
        return [
            [
                'post_genre',
                [
                    'category' => [
                        'id' => 1,
                        'name' => 'African'
                    ],
                    'name' => self::DUMMY_GENRE_NAME,
                    'description' => self::DUMMY_GENRE_NAME . ' description.',
                    'info' => self::DUMMY_GENRE_NAME . ' info.'
                ]
            ]
        ];
    }

    /**
     * @dataProvider postActionDataProvider
     */
    public function testPostAction($route, $data)
    {
        $router = $this->getContainer()->get('router');
        $url = $router->generate($route, [], $router::ABSOLUTE_URL);

        $authHeaders = $this->getAuthHeaders();
        list($resp, $info) = $this->getResponse('post', $url, json_encode($data), $authHeaders);
        $rdata = json_decode($resp, true);

        $this->assertArrayHasKey('id', $rdata, $resp);
        $this->assertNotNull($rdata['id'], $resp);
        $this->assertGreaterThan(0, $rdata['id'], $resp);

        unset($rdata['id']);
        $this->assertEquals($data, $rdata, $resp);
    }

    public function getAuthHeaders() {
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

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $qb = $em->getRepository(Genre::class)->createQueryBuilder('o')
            ->delete()
            ->andWhere('o.name = :name')
            ->setParameters(['name' => self::DUMMY_GENRE_NAME])
        ;
        $qb->getQuery()->execute();
    }
}
