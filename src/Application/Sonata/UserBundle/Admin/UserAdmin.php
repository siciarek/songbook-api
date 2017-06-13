<?php

namespace Application\Sonata\UserBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;

class UserAdmin extends SonataUserAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->tab('Additional')
                ->with('Additional user data')
                    ->add('level')
                    ->add('description')
                    ->add('info')
                    ->add('profileVisibleToThePublic')
                ->end()
            ->end()
        ;
    }
}