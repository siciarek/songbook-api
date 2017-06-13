<?php

namespace Tests\AuthBundle;

use Tests\TestCase;
use Application\Sonata\UserBundle\Entity\User;

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
        $headers = $this->getHeaders($username, $password);

        list($resp, $info) = $this->getResponse('GET', $securedPageUrl, null, $headers);
        $this->assertEquals(200, $info['http_code'], $resp);
        $data = json_decode($resp, true);

        $this->assertArrayHasKey('username', $data, json_encode($data, 128));
        $this->assertEquals($username, $data['username'], $resp);
    }


    /**
     * @dataProvider postDataProvider
     * @group post
     */
    public function testPost($securedPageRoute, $authRoute, $username, $password)
    {
        $faker = \Faker\Factory::create('pl_PL');

        $router = $this->getContainer()->get('router');
        $securedPageUrl = $router->generate($securedPageRoute, [], $router::ABSOLUTE_URL);
        $headers = $this->getHeaders($username, $password);

        list($resp, $info) = $this->getResponse('GET', $securedPageUrl, null, $headers);
        $this->assertEquals(200, $info['http_code'], $resp);
        $data = json_decode($resp, true);

        $this->assertArrayHasKey('username', $data, json_encode($data, 128));
        $this->assertEquals($username, $data['username'], $resp);

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $temp = $em->getRepository(User::class)->find($data['id']);

        $before = [
            'id' => $temp->getId(),
            'gender' => $temp->getGender(),
            'firstName' => $temp->getFirstName(),
            'lastName' => $temp->getFirstName(),
            'dateOfBirth' => $temp->getDateOfBirth(),
            'email' => $temp->getEmail(),
            'description' => $temp->getDescription(),
            'level' => $temp->getLevel(),
            'profileVisibleToThePublic' => $temp->getProfileVisibleToThePublic(),
        ];

        $new = array_flip(array_keys($before));
        $new['id'] = $before['id'];

        do {
            $new['gender'] = rand(0, 1) ? User::GENDER_MALE : User::GENDER_FEMALE;
        } while ($data['gender'] === $new['gender']);

        do {
            $new['firstName'] = $new['gender'] === User::GENDER_MALE ? $faker->firstNameMale : $faker->firstNameFemale;
        } while ($data['firstName'] === $new['firstName']);

        do {
            $new['lastName'] = $new['gender'] === User::GENDER_MALE ? $faker->lastNameMale : $faker->lastNameFemale;
        } while ($data['lastName'] === $new['lastName']);

        do {
            $new['dateOfBirth'] = $faker->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d');
        } while ($data['dateOfBirth'] === $new['dateOfBirth']);

        do {
            $new['level'] = rand(1, 100);
        } while ($data['level'] === $new['level']);

        do {
            $new['description'] = $faker->sentence;
        } while ($data['description'] === $new['description']);

        do {
            $new['info'] = implode("\n", $faker->sentences(6));
        } while ($data['info'] === $new['info']);

        do {
            $new['profileVisibleToThePublic'] = rand(0, 1) > 0;
        } while ($data['profileVisibleToThePublic'] === $new['profileVisibleToThePublic']);

        setlocale(LC_ALL, "pl_PL.utf8");
        $first = mb_convert_case(iconv('UTF-8', 'ASCII//TRANSLIT', $new['firstName']), MB_CASE_LOWER);
        $second = mb_convert_case(iconv('UTF-8', 'ASCII//TRANSLIT', $new['lastName']), MB_CASE_LOWER);
        $sep = rand(1, 0) > 0 ? '.' : (rand(1, 0) ? '-' : '');
        $first = rand(1, 0) > 0 ? $first[0] : $first;

        $new['email'] = sprintf('%s%s%s@%s',
            $first, $sep, $second,
            $faker->safeEmailDomain
        );

        foreach ($new as $key => $val) {
            $data[$key] = $val;
        }

        list($resp, $info) = $this->getResponse('POST', $securedPageUrl, json_encode($data), $headers);
        $this->assertEquals(204, $info['http_code'], $resp);

        $em->refresh($temp);

        $temp = $this->getContainer()->get('jms_serializer')->serialize($temp, 'json');
        $after = json_decode($temp, true);

        $this->assertEquals($before['id'], $after['id']);

        foreach ($before as $key => $val) {
            if ($key === 'id') {
                continue;
            }
            $this->assertNotEquals($before[$key], $after[$key], "Invalid value in field '$key'");
            $this->assertNotEquals($new[$key], $val, "Invalid value in field '$key' expected: '{$new[$key]}'");
        }
    }

    /**
     * @param $username
     * @param $password
     * @return array
     */
    public function getHeaders($username, $password, $authRoute = 'auth_check'): array
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
