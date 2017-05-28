<?php

namespace Tests\AuthBundle;

use Tests\TestCase;

class JwtAuthTest extends TestCase
{
    public static function authDataProvider()
    {
        $authUrl = 'auth_check';

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
        $securedPageUrl = 'user.dashboard';

        return array_map(function ($e) use ($securedPageUrl) {
            array_unshift($e, $securedPageUrl);
            return $e;
        }, self::authDataProvider());
    }

    /**
     * @dataProvider securedPageDataProvider
     */
    public function testSecuredPageAccess($securedPageRoute, $authRoute, $username, $password)
    {
        $router = $this->getContainer()->get('router');
        $securedPageUrl = $router->generate($securedPageRoute, [], $router::ABSOLUTE_URL);
        $authUrl = $router->generate($authRoute, [], $router::ABSOLUTE_URL);

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
    public function testAuthFailure($authRoute, $username, $password)
    {
        $router = $this->getContainer()->get('router');
        $authUrl = $router->generate($authRoute, [], $router::ABSOLUTE_URL);

        $authData = [
            'username' => $username,
            'password' => $password,
        ];
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $authData['username'] .= 'broken';

        list($resp, $info) = $this->getResponse('POST', $authUrl, $authData, $headers);
        $data = json_decode($resp, true);

        # Invalid auth data:
        $this->assertInternalType('array', $data);
        $this->assertEquals(['code' => 401, 'message' => 'Bad credentials'], $data);
        $this->assertEquals(401, $info['http_code']);

        # Invalid auth protocol:
        foreach (['GET', 'PUT', 'DELETE'] as $method) {
            list($resp, $info) = $this->getResponse($method, $authUrl);
            $data = json_decode($resp, true);
            $this->assertNotNull($data);
            $this->assertEquals(['code' => 404, 'message' => 'Not Found'], $data);

            $this->assertEquals(404, $info['http_code']);
        }
    }

    /**
     * @dataProvider authDataProvider
     */
    public function testAuthSuccess($authRoute, $username, $password)
    {
        $router = $this->getContainer()->get('router');
        $authUrl = $router->generate($authRoute, [], $router::ABSOLUTE_URL);

        $authData = [
            'username' => $username,
            'password' => $password,
        ];
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        list($resp, $info) = $this->getResponse('POST', $authUrl, $authData, $headers);

        # Check if data has all the required elements:
        $data = json_decode($resp, true);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('token', $data);
        $this->assertRegExp('/^[\w+\-\.]+$/', $data['token']);

        # If JWT token listeners are not implemented remove 5 following lines:
        $this->assertArrayHasKey('data', $data, $resp);
        $this->assertArrayHasKey('username', $data['data']);
        $this->assertNotNull($data['data']['username']);
        $this->assertArrayHasKey('roles', $data['data']);
        $this->assertInternalType('array', $data['data']['roles']);
    }
}
