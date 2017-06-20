<?php

namespace Tests\AuthBundle;

use Tests\TestCase;

/**
 * @group lyrics
 */
class LyricsControllerTest extends TestCase
{
    public function testCgetAction()
    {
        $router = $this->getContainer()->get('router');
        $url = $router->generate('cget_lyrics', [], $router::ABSOLUTE_URL);
        $this->assertNotNull($url);

        list($resp, $info) = $this->getResponse('GET', $url);
        $data = json_decode($resp, true);

        $this->assertTrue(is_array($data), $resp);

        if (count($data) > 0) {
            foreach ($data as $rec) {
                $this->assertTrue(is_array($rec), $rec);
                foreach (['id', 'title', 'lyrics'] as $key) {
                    $this->assertArrayHasKey($key, $rec);
                }
            }
        }
        return $data;
    }

    /**
     * @depends testCgetAction
     */
    public function testGetAction(array $data)
    {
        $router = $this->getContainer()->get('router');

        foreach ($data as $rec) {
            $url = $router->generate('get_lyrics', ['item' => $rec['id']], $router::ABSOLUTE_URL);
            $this->assertNotNull($url);

            list($resp, $info) = $this->getResponse('GET', $url);
            $el = json_decode($resp, true);

            $this->assertTrue(is_array($el), $resp);

            $this->assertTrue(is_array($el), json_encode($el));
            $this->assertEquals([], array_diff(array_keys($el), [
                'id',
                'title',
                'description',
                'info',
                'lyrics',
                'genre',
                'authors',
                'artists',
                'audio',
                'video',
                'audioCount',
                'videoCount',
                'firstPublishedAt',
            ]));
        }
    }
}
