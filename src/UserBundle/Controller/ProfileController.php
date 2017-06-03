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
        $criteria = ['username' => $this->getUser()->getUsername()];

        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy($criteria)
            ;

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmailCanonical(),
            'gender' => $user->getGender(),
            'dateOfBirth' => $user->getDateOfBirth(),
            'level' => $user->getLevel(),
        ];

        return $data;
    }

    public function putAction(Request $request)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        # TODO: change to fos user
        $criteria = ['username' => $this->getUser()->getUsername()];
        $man = $this->get('fos_user.user_manager');
        $user = $man->findUserBy($criteria);

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setEmail($data['email']);
        $user->setGender($data['gender']);
        $user->setLevel($data['level']);
        $man->updateUser($user);

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmailCanonical(),
            'gender' => $user->getGender(),
            'dateOfBirth' => $user->getDateOfBirth(),
            'level' => $user->getLevel(),
        ];

        return $data;
    }
}
