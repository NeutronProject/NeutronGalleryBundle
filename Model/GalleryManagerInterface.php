<?php
namespace Neutron\Plugin\GalleryBundle\Model;

interface GalleryManagerInterface
{
    public function getQueryBuilderForGalleryManagementDataGrid();
}