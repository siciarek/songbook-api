<?php

namespace Tests\AuthBundle;

use Tests\TestCase;

class JwtAuthTest extends TestCase
{
    public static function authDataProvider()
    {
        $authUrl = 'http://localhost:8000/api/auth';

        return [
            [
                $authUrl,
                'jsiciarek',
                'pass',
            ],
            [
                $authUrl,
                'colak',
                'pass',
            ],
            [
                $authUrl,
                'molak',
                'pass',
            ],
        ];
    }

    public static function securedPageDataProvider()
    {
        $authUrl = 'http://localhost:8000/api/auth';
        $securedPageUrl = 'http://localhost:8000/api/user/dashboard';

        return [
            [
                $securedPageUrl,
                $authUrl,
                'jsiciarek',
                'pass',
            ],
            [
                $securedPageUrl,
                $authUrl,
                'colak',
                'pass',
            ],
            [
                $securedPageUrl,
                $authUrl,
                'molak',
                'pass',
            ],
        ];
    }

    /**
     * @dataProvider securedPageDataProvider
     */
    public function testSecuredPageAccess($securedPageUrl, $authUrl, $username, $password)
    {
        # Unauthenticated users should have no access to secured resource:
        list($resp, $info) = $this->getResponse('GET', $securedPageUrl);
        $data = json_decode($resp, true);
        $this->assertEquals($data, ['code' => 401, 'message' => 'Bad credentials']);
        $this->assertEquals(401, $info['http_code']);

        # Authentication:
        $authData = [
            'username' => $username,
            'password' => $password,
        ];
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];
        list($resp, $info) = $this->getResponse('POST', $authUrl, $authData, $headers);
        $data = json_decode($resp, true);

        # Valid token:
        $token = $data['token'];
        $headers = [
            sprintf('Authorization: Bearer %s', $token),
        ];
        list($resp, $info) = $this->getResponse('GET', $securedPageUrl, null, $headers);
        $this->assertEquals(200, $info['http_code']);

        # Ivalid token:
        $token = str_replace('A', 'C', $data['token']);
        $headers = [
            sprintf('Authorization: Bearer %s', $token),
        ];
        list($resp, $info) = $this->getResponse('GET', $securedPageUrl, null, $headers);
        $this->assertEquals(401, $info['http_code']);
    }

    /**
     * @dataProvider authDataProvider
     */
    public function testAuthFailure($url, $username, $password)
    {
        $authData = [
            'username' => $username,
            'password' => $password,
        ];
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $authData['username'] .= 'broken';

        list($resp, $info) = $this->getResponse('POST', $url, $authData, $headers);
        $data = json_decode($resp, true);

        # Invalid auth data:
        $this->assertInternalType('array', $data);
        $this->assertEquals(['code' => 401, 'message' => 'Bad credentials'], $data);
        $this->assertEquals(401, $info['http_code']);

        # Invalid auth protocol:
        foreach (['GET', 'PUT', 'DELETE'] as $method) {
            list($resp, $info) = $this->getResponse($method, $url);
            $data = json_decode($resp, true);
            $this->assertEquals(404, $info['http_code']);

            # TODO: fully JSON app
            # $this->assertNotNull($data);
        }
    }

    /**
     * @dataProvider authDataProvider
     */
    public function testAuthSuccess($url, $username, $password)
    {
        $authData = [
            'username' => $username,
            'password' => $password,
        ];
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        list($resp, $info) = $this->getResponse('POST', $url, $authData, $headers);


        # Check if data has all the required elements:
        $data = json_decode($resp, true);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('token', $data);
        $this->assertRegExp('/^[\w+\-\.]+$/', $data['token']);

        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('username', $data['data']);
        $this->assertNotNull($data['data']['username']);

        $this->assertArrayHasKey('roles', $data['data']);
        $this->assertInternalType('array', $data['data']['roles']);
    }
}
