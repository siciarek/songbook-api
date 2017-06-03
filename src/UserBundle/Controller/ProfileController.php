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
        # TODO: change to fos user
        $criteria = ['username' => $this->getUser()->getUsername()];
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy($criteria)
            ;

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmailCanonical(),
        ];

        return $data;
    }

    public function postAction() {

        # TODO: change to fos user
        $criteria = ['username' => $this->getUser()->getUsername()];
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy($criteria)
        ;

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmailCanonical(),
        ];

        return $data;
    }

    public function putAction() {

        # TODO: change to fos user
        $criteria = ['username' => $this->getUser()->getUsername()];
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy($criteria)
        ;

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmailCanonical(),
        ];

        return $data;
    }
}
