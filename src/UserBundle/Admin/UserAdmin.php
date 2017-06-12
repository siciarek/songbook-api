<?php
namespace UserBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends Admin
{

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('enabled', null, [
                'editable' => true,
            ])
            ->addIdentifier('firstName')
            ->addIdentifier('lastName')
            ->add('roles')
            ->add('groups')
            ->add('description')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'show' => [],
                ]
            ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('General')
                ->with('')
                    ->add('enabled')
                    ->add('username')
                    ->add('email')
                    ->add('firstName')
                    ->add('lastName')
                    ->add('dateOfBirth', 'sonata_type_date_picker')
                ->end()
            ->end()
            ->tab('Credentials')
                ->with('')
                    ->add('groups')
                ->end()
            ->end()
            ->tab('Additional')
                ->with('')
                    ->add('description')
                    ->add('info')
                    ->add('level')
                    ->add('profileVisibleToThePublic')
                ->end()
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->tab('General')
                ->with('')
                    ->add('id')
                    ->add('enabled')
                    ->add('username')
                ->end()
            ->end()
            ->tab('Extended')
                ->with('')
                    ->add('firstName')
                    ->add('lastName')
                    ->add('dateOfBirth')
                    ->add('email')
                ->end()
            ->end()
            ->tab('Credentials')
                ->with('')
                    ->add('groups')
                    ->add('roles')
                ->end()
            ->end()
            ->tab('Additional')
                ->with('')
                    ->add('description')
                    ->add('info')
                    ->add('level')
                    ->add('profileVisibleToThePublic')
                ->end()
            ->end()
        ;
    }
}