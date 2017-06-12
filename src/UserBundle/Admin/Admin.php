<?php

namespace UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;

abstract class Admin extends AbstractAdmin
{
    protected $exportDateFormat = 'Y-m-d H:i';
    protected $maxPerPage = 25;
    protected $maxPageLinks = 16;
    protected $supportsPreviewMode = false;

    public function getDataSourceIterator()
    {
        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();

        $dataSourceIterator = $this->getModelManager()->getDataSourceIterator($datagrid, $this->getExportFields());

        if ($dataSourceIterator instanceof \Exporter\Source\DoctrineORMQuerySourceIterator) {
            $dataSourceIterator->setDateTimeFormat($this->exportDateFormat);
        }

        return $dataSourceIterator;
    }

    public function getContainer()
    {
        return $this->getConfigurationPool()->getContainer();
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array(
            'xls',
            'csv',
        );
    }
}
