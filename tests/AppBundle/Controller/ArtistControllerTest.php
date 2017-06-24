<?php

namespace Tests\AppBundle\Controller;

use Tests\TestCase;

/**
 * @group artist
 */
class ArtistControllerTest extends TestCase
{
    public function testCgetAction($route = 'cget_artist')
    {
        $router = $this->getContainer()->get('router');
        $url = $router->generate($route, [], $router::ABSOLUTE_URL);
        $this->assertNotNull($url);

        $client = self::createClient();
        $client->request('GET', $url);
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(is_array($data));

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
}
