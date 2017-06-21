<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ArtistAdmin extends Admin
{

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('song.title')
            ->addIdentifier('path', 'url')
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
            ->add('song.title')
            ->add('path', 'url')
            ->add('description')
            ->add('info')
            ->add('artists');
    }
}