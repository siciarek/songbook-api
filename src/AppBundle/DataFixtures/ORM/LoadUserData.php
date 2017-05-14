<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\Yaml\Yaml;

class LoadUserData extends BasicFixture {

    /**
     * @var int
     */
    protected $order = 2;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {

        $path = __DIR__ . '/../data/User.yml';


        $path = realpath($path);
        $data = Yaml::parse(file_get_contents($path));
        $data = array_pop($data);

        // MOCK:
        # $data = $this->addMockUsers($data);

        /**
         * @var UserManager $mngr
         */
        $mngr = $this->getContainer()->get('fos_user.user_manager');

        $count = 0;

        foreach ($data as $o) {
            $user = $mngr->createUser();
            $user->setEnabled($o['enabled']);
            $user->setUsername($o['username']);
            $user->setEmail($o['email']);
            $user->setPlainPassword($o['password']);

            $user->setFirstName($o['firstname']);
            $user->setLastName($o['lastname']);
            $user->setGender($o['gender']);
            $user->setDateOfBirth(new \DateTime($o['dob']));

            foreach ($o['groups'] as $group) {
                $user->addGroup($this->getReference('group' . $group));
            }

            $mngr->updateUser($user);
            $this->setReference('user' . $user->getUsername(), $user);

            if(++$count % 50 === 0) {
                var_dump($count);
            }
        }
    }

    protected function addMockUsers($data, $count = 100) {

        $createEmail = function($firstname, $lastname) {
            $domain = \Faker\Provider\pl_PL\Internet::safeEmailDomain();

            $user = '';

            switch(rand(1, 4)) {
                case 1:
                    $user = sprintf('%s.%s', $firstname, $lastname);
                    break;
                case 2:
                    $user = sprintf('%s.%s', $firstname[0], $lastname);
                    break;
                case 3:
                    $user = sprintf('%s%s', $firstname[0], $lastname);
                    break;
                case 4:
                    $user = sprintf('%s.%s', $lastname, $firstname);
                    break;
            }

            $user = mb_convert_case($user, MB_CASE_LOWER, 'UTF-8');
            $user = transliterator_transliterate('Any-Latin; Latin-ASCII', $user);

            return sprintf('%s@%s', $user, $domain);
        };

        $obj = [
            'enabled' => true,
            'username' => null,
            'firstname' => null,
            'lastname' => null,
            'dob' => null,
            'gender' => 'u',
            'password' => 'pass',
            'email' => null,
            'groups' => [],
        ];

        $list = [];
        $list = array_merge($data, $list);

        foreach(range(1, $count) as $i) {

            do {
                $obj['gender'] = 'm';
                $obj['firstname'] = \Faker\Provider\pl_PL\Person::firstNameMale();
                $obj['lastname'] = \Faker\Provider\pl_PL\Person::lastNameMale();


                // Create female users:
                if (rand(0, 1) > 0) {
                    $obj['gender'] = 'f';
                    $obj['firstname'] = \Faker\Provider\pl_PL\Person::firstNameFemale();
                    $obj['lastname'] = \Faker\Provider\pl_PL\Person::lastNameFemale();
                }

                $obj['dob'] = \Faker\Provider\DateTime::dateTimeBetween('-42 years', '-21 years')->format('Y-m-d');

                $obj['email'] = $createEmail($obj['firstname'], $obj['lastname']);
                $obj['username'] = $obj['email'];

                // less than 20% of fake users are Admins
                $obj['groups'] = rand(1, 100) < 20 ? ['Admins'] : ['Users'];

                $temp = array_filter($list, function($e) use ($obj) {
                    return $e['username'] === $obj['username'];
                });
                $temp = array_values($temp);

                $exists = count($temp) > 0;

            } while ($exists === true);

            $list[] = $obj;
        }

        return $list;
    }
}
