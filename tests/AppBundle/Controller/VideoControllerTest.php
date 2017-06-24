<?php

namespace Tests\AppBundle\Controller;

use Tests\TestCase;

/**
 * @group video
 */
class VideoControllerTest extends TestCase
{
    public function testCgetAction()
    {
        $router = $this->getContainer()->get('router');
        $url = $router->generate('cget_video', [], $router::ABSOLUTE_URL);
        $this->assertNotNull($url);

        list($resp, $info) = $this->getResponse('GET', $url);
        $data = json_decode($resp, true);

        $this->assertTrue(is_array($data), $resp);

        if (count($data) > 0) {
            foreach ($data as $rec) {
                $this->assertTrue(is_array($rec), $rec);
                foreach (['id', 'title', 'video'] as $key) {
                    $this->assertArrayHasKey($key, $rec);
                    if (in_array($key, ['video', 'videoCount'])) {
                        $this->assertTrue(is_array($rec[$key]), $key);
                    }
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
            $url = $router->generate('get_video', ['item' => $rec['id']], $router::ABSOLUTE_URL);
            $this->assertNotNull($url);

            list($resp, $info) = $this->getResponse('GET', $url);
            $dat = json_decode($resp, true);

            $this->assertTrue(is_array($dat), $resp);

            foreach ($dat as $el) {
                $this->assertTrue(is_array($el), json_encode($el));
                $this->assertEquals([], array_diff(array_keys($el), ['id', 'path', 'description', 'info', 'song', 'artists']));
            }
        }
    }
}
