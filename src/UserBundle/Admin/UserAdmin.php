<?php
namespace UserBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

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
            ->addIdentifier('email')
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
            ->add('enabled')
            ->add('firstName')
            ->add('lastName')
            ->add('username')
            ->add('email')
            ->add('description')
            ->add('info')
        ;
    }
}