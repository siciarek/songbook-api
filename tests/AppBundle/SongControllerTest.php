<?php

namespace Tests\AuthBundle;

use AppBundle\Entity\Song;
use Tests\TestCase;

/**
 * @group song
 */
class SongControllerTest extends TestCase
{
    const DUMMY_SONG_TITLE = 'Dummy Song';

    public static function postActionDataProvider()
    {
        $lyrics =<<<LYRICS
Była sobie mała caca.
Miała lalkę i pajaca.
Ten pajacyk miał sprężynkę
A laleczka smutną minkę.
LYRICS;

        return [
            [
                'post_song',
                [
                    'genre' => [
                        'id' => 691,
                        'name' => 'Rock',
                        'description' => null,
                        'info' => null,
                        'category' => [
                            'id' => 18,
                            'name' => 'Rock',
                        ]
                    ],
                    'title' => self::DUMMY_SONG_TITLE,
                    'lyrics' => $lyrics,
                    'description' => self::DUMMY_SONG_TITLE . ' description.',
                    'info' => self::DUMMY_SONG_TITLE . ' info.',
                    'audioCount' => 0,
                    'videoCount' => 0,
                    'audio' => [],
                    'video' => null,
                    'createdAt' => '1966-10-21', # date('Y-m-d\\TH:i:sO', strtotime('1966-10-21')),
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
        $this->assertEquals($data, $rdata);
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
        $qb = $em->getRepository(Song::class)->createQueryBuilder('o')
            ->delete()
            ->andWhere('o.title = :title')
            ->setParameters(['title' => self::DUMMY_SONG_TITLE])
        ;
        $qb->getQuery()->execute();
    }
}
