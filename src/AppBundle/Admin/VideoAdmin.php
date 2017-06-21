<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class VideoAdmin extends Admin
{

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('path', 'url')
            ->add('description')
            ->add('artists')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'show' => [],
                ]
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('path', 'url')
            ->add('description')
            ->add('info')
            ->add('artists');
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('path', 'url')
            ->add('description')
            ->add('info')
            ->add('artists');
    }
}