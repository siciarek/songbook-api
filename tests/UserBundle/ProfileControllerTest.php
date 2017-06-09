<?php

namespace Tests\AuthBundle;

use Tests\TestCase;
use UserBundle\Entity\User;

/**
 * @group user
 */
class ProfileControllerTest extends TestCase
{
    public static function getDataProvider()
    {
        return array_map(function ($e) {
            array_unshift($e, 'get_profile');
            return $e;
        }, JwtAuthTest::authDataProvider());
    }

    public static function postDataProvider()
    {
        return array_map(function ($e) {
            array_unshift($e, 'post_profile');
            return $e;
        }, JwtAuthTest::authDataProvider());
    }

    /**
     * @dataProvider getDataProvider
     */
    public function testGet($securedPageRoute, $authRoute, $username, $password)
    {
        $router = $this->getContainer()->get('router');
        $securedPageUrl = $router->generate($securedPageRoute, [], $router::ABSOLUTE_URL);
        $headers = $this->getHeaders($authRoute, $username, $password);

        list($resp, $info) = $this->getResponse('GET', $securedPageUrl, null, $headers);
        $this->assertEquals(200, $info['http_code'], $resp);
        $data = json_decode($resp, true);

        $this->assertArrayHasKey('username', $data, json_encode($data, 128));
        $this->assertEquals($username, $data['username'], $resp);
    }


    /**
     * @dataProvider postDataProvider
     */
    public function testPost($securedPageRoute, $authRoute, $username, $password)
    {
        $faker = \Faker\Factory::create('pl_PL');

        $router = $this->getContainer()->get('router');
        $securedPageUrl = $router->generate($securedPageRoute, [], $router::ABSOLUTE_URL);
        $headers = $this->getHeaders($authRoute, $username, $password);

        list($resp, $info) = $this->getResponse('GET', $securedPageUrl, null, $headers);
        $this->assertEquals(200, $info['http_code'], $resp);
        $data = json_decode($resp, true);

        $this->assertArrayHasKey('username', $data, json_encode($data, 128));
        $this->assertEquals($username, $data['username'], $resp);

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $temp = $em->getRepository(User::class)->find($data['id']);

        $before = [
            'id' => $temp->getId(),
            'level' => $temp->getLevel(),
            'gender' => $temp->getGender(),
            'firstName' => $temp->getFirstName(),
            'lastName' => $temp->getFirstName(),
            'profileVisibleToThePublic' => $temp->getProfileVisibleToThePublic(),
        ];

        $new = array_flip(array_keys($before));
        $new['id'] = $before['id'];

        do {
            $new['gender'] = rand(0, 1) ? 'male' : 'female';
        } while($data['gender'] === $new['gender']);


        do {
            $new['firstName'] = $new['gender'] === 'male' ? $faker->firstNameMale : $faker->firstNameFemale;
        } while($data['firstName'] === $new['firstName']);

        do {
            $new['lastName'] = $new['gender'] === 'male' ? $faker->lastNameMale : $faker->lastNameFemale;
        } while($data['lastName'] === $new['lastName']);

        do {
            $new['level'] = rand(1, 100);
        } while($data['level'] === $new['level']);

        do {
            $new['profileVisibleToThePublic'] = rand(0, 1) > 0;
        } while($data['profileVisibleToThePublic'] === $new['profileVisibleToThePublic']);

        foreach($new as $key => $val) {
            $data[$key] = $val;
        }

        list($resp, $info) = $this->getResponse('POST', $securedPageUrl, json_encode($data), $headers);
        $this->assertEquals(204, $info['http_code'], $resp);

        $em->refresh($temp);

        $after = [
            'id' => $temp->getId(),
            'level' => $temp->getLevel(),
            'gender' => $temp->getGender(),
            'firstName' => $temp->getFirstName(),
            'lastName' => $temp->getFirstName(),
            'profileVisibleToThePublic' => $temp->getProfileVisibleToThePublic(),
        ];

        $this->assertEquals($before['id'], $after['id']);
        foreach($before as $key => $val) {
            if($key === 'id') {
                continue;
            }
            $this->assertNotEquals($before[$key], $after[$key], "Invalid value in field '$key'");
            $this->assertNotEquals($new[$key], $val, "Invalid value in field '$key' expected: '{$new[$key]}'");
        }
    }


    /**
     * @param $securedPageRoute
     * @param $authRoute
     * @param $username
     * @param $password
     * @return array
     */
    public function getHeaders($authRoute, $username, $password): array
    {
        $authData = [
            'username' => $username,
            'password' => $password,
        ];

        $router = $this->getContainer()->get('router');
        $authUrl = $router->generate($authRoute, [], $router::ABSOLUTE_URL);

        # Authentication:
        list($resp, $info) = $this->getResponse('POST', $authUrl, $authData);
        $data = json_decode($resp, true);

        # Valid token:
        $token = $data['token'];
        $headers = [
            sprintf('Authorization: Bearer %s', $token),
        ];

        return $headers;
    }
}
