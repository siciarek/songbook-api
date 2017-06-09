<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use UserBundle\Entity\User;

/**
 * @RouteResource("Profile", pluralize=false)
 */
class ProfileController extends FOSRestController implements ClassResourceInterface
{
    public function getAction()
    {
        return $this->getUser();
    }

    public function postAction(Request $request)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        $man = $this->get('fos_user.user_manager');
        $user = $this->getUser();

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setDateOfBirth(new \DateTime($data['dateOfBirth']));
        $user->setEmail($data['email']);
        $user->setGender($data['gender']);
        $user->setLevel($data['level']);
        $user->setProfileVisibleToThePublic($data['profileVisibleToThePublic']);
        $user->setInfo($data['info']);

        $man->updateUser($user);
    }
}
