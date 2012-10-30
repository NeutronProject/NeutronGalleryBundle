<?php
namespace Neutron\Plugin\GalleryBundle\Doctrine;

use Neutron\Plugin\GalleryBundle\Model\GalleryManagerInterface;

use Neutron\ComponentBundle\Doctrine\AbstractManager;

class GalleryManager extends AbstractManager implements GalleryManagerInterface
{
    public function getQueryBuilderForGalleryManagementDataGrid()
    {
        return $this->getQueryBuilderForGalleryManagementDataGrid();
    }
}