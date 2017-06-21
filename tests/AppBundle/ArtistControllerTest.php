<?php

namespace Tests\AuthBundle;

use AppBundle\Entity\Artist;
use Tests\TestCase;

/**
 * @group artist
 */
class ArtistControllerTest extends TestCase
{
    public function testCgetAction()
    {
        $router = $this->getContainer()->get('router');
        $url = $router->generate('cget_artist', [], $router::ABSOLUTE_URL);
        $this->assertNotNull($url);

        list($resp, $info) = $this->getResponse('GET', $url);
        $data = json_decode($resp, true);

        $this->assertTrue(is_array($data), $resp);

        if (count($data) > 0) {
            foreach ($data as $rec) {
                $this->assertTrue(is_array($rec), $rec);
                foreach (['id', 'name', 'firstName', 'lastName'] as $key) {
                    $this->assertArrayHasKey($key, $rec);
                    if (in_array($key, ['songs', 'videos', 'audios'])) {
                        $this->assertTrue(is_array($rec[$key]), $key);
                    }
                }
            }
        }
        return $data;
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
