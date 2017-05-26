<?php

namespace Tests\AppBundle\Controller;

use PHPUnit\Framework\TestCase;

class JwtAuthTest extends TestCase
{
    public function testAuthFailure()
    {
        $authData = $this->getAuthData();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $url = 'http://localhost:8000/api/auth';

        $authData['username'] .= 'broken';

        list($resp, $info) = $this->getResponse('POST', $url, $authData, $headers);
        $data = json_decode($resp, true);

        # Invalid auth data:
        $this->assertInternalType('array', $data);
        $this->assertEquals(['code' => 401, 'message' => 'Bad credentials'], $data);
        $this->assertEquals(401, $info['http_code']);

        # Invalid auth protocol:
        foreach(['GET', 'PUT', 'DELETE'] as $method) {
            list($resp, $info) = $this->getResponse($method, $url);
            $this->assertEquals(404, $info['http_code']);
        }
    }

    public function testAuthSuccess()
    {
        $authData = $this->getAuthData();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $url = 'http://localhost:8000/api/auth';

        list($resp, $info) = $this->getResponse('POST', $url, $authData, $headers);

        $data = json_decode($resp, true);

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('token', $data);
        $this->assertRegExp('/^[\w+\-\.]+$/', $data['token']);

        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('username', $data['data']);
        $this->assertArrayHasKey('roles', $data['data']);
        $this->assertInternalType('array', $data['data']['roles']);
    }

    public function getResponse($method, $url, $data = [], $headers = null)
    {
        $ch = curl_init();

        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        switch ($method) {
            case 'POST':
                $data = http_build_query($data);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return [$resp, $info];
    }

    public function getAuthData()
    {
        return [
            'username' => 'molak',
            'password' => 'pass',
        ];
    }
}
