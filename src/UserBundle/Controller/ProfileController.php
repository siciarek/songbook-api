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
        $new = $this
            ->get('jms_serializer')
            ->deserialize($request->getContent(), User::class, 'json');

        $user = $this->getUser();
        $user->setUsername($new->getUsername());
        $user->setFirstName($new->getFirstName());
        $user->setLastName($new->getLastName());
        $user->setDateOfBirth($new->getDateOfBirth());
        $user->setEmail($new->getEmail());
        $user->setGender($new->getGender());
        $user->setLevel($new->getLevel());
        $user->setProfileVisibleToThePublic($new->getProfileVisibleToThePublic());
        $user->setInfo($new->getInfo());
        $user->setDescription($new->getDescription());


        $this->get('fos_user.user_manager')->updateUser($user);
    }
}
