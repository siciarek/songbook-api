<?php

namespace Tests\AuthBundle;

use Tests\TestCase;

/**
 * @group auth
 */
class JwtAuthTest extends TestCase
{
    public static function authDataProvider()
    {
        return [
            [
                'auth_check',
                'jsiciarek',
                'pass',
            ],
            [
                'auth_check',
                'colak',
                'pass',
            ],
            [
                'auth_check',
                'molak',
                'pass',
            ],
        ];
    }

    public static function securedPageDataProvider()
    {
        $ret = [];

        foreach(['get_dashboard', 'get_profile', 'ping'] as $route) {
            $temp = array_map(function ($e) use ($route) {
                array_unshift($e, $route);
                array_push($e, $route === 'ping' ? 204 : 200);
                return $e;
            }, self::authDataProvider());

            $ret = array_merge($ret, $temp);
        }

        return $ret;
    }

    /**
     * @dataProvider securedPageDataProvider
     */
    public function testSecuredPageAccess($securedPageRoute, $authRoute, $username, $password, $ret)
    {
        $authData = [
            'username' => $username,
            'password' => $password,
        ];

        $router = $this->getContainer()->get('router');
        $securedPageUrl = $router->generate($securedPageRoute, [], $router::ABSOLUTE_URL);
        $authUrl = $router->generate($authRoute, [], $router::ABSOLUTE_URL);

        # Unauthenticated users should have no access to secured resource:
        list($resp, $info) = $this->getResponse('GET', $securedPageUrl);
        $data = json_decode($resp, true);
        unset($data['data']);
        $this->assertEquals($data, ['code' => 401, 'message' => 'Bad credentials']);
        $this->assertEquals(401, $info['http_code']);

        # Authentication:
        list($resp, $info) = $this->getResponse('POST', $authUrl, $authData);
        $data = json_decode($resp, true);

        # Valid token:
        $token = $data['token'];
        $headers = [
            sprintf('Authorization: Bearer %s', $token),
        ];
        list($resp, $info) = $this->getResponse('GET', $securedPageUrl, null, $headers);
        $this->assertEquals($ret, $info['http_code']);

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

        $authData['username'] .= 'broken';

        list($resp, $info) = $this->getResponse('POST', $authUrl, $authData);
        $data = json_decode($resp, true);

        # Invalid auth data:
        $this->assertInternalType('array', $data);
        unset($data['data']);
        $this->assertEquals(['code' => 401, 'message' => 'Bad credentials'], $data);
        $this->assertEquals(401, $info['http_code']);

        # Invalid auth protocol:
        foreach (['GET', 'PUT', 'DELETE'] as $method) {
            list($resp, $info) = $this->getResponse($method, $authUrl);
            $data = json_decode($resp, true);
            $this->assertNotNull($data);
            unset($data['data']);
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

        # Test multiple form modes:
        foreach (['multi', 'urlencoded'] as $mode) {

            switch ($mode) {
                case 'urlencoded':
                    $headers = [
                        'Content-Type: application/x-www-form-urlencoded',
                    ];
                    list($resp, $info) = $this->getResponse('POST', $authUrl, http_build_query($authData), $headers);
                    break;

                case 'multi':
                    list($resp, $info) = $this->getResponse('POST', $authUrl, $authData);
                    break;
            }

            $this->assertEquals(200, $info['http_code']);

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
}
