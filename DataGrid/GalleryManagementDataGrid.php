<?php
namespace Neutron\Plugin\GalleryBundle\DataGrid;

use Neutron\Plugin\GalleryBundle\Model\GalleryManagerInterface;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Neutron\Bundle\DataGridBundle\DataGrid\FactoryInterface;

class GalleryManagementDataGrid
{

    const IDENTIFIER = 'neutron_gallery_management';
    
    protected $factory;
    
    protected $translator;
    
    protected $router;
    
    protected $manager;
    
    protected $translationDomain;

    public function __construct (FactoryInterface $factory, Translator $translator, Router $router, 
             GalleryManagerInterface $manager, $translationDomain)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->router = $router;
        $this->manager = $manager;
        $this->translationDomain = $translationDomain;
    }

    public function build ()
    {
        
        $dataGrid = $this->factory->createDataGrid(self::IDENTIFIER);
        $dataGrid
            ->setCaption(
                $this->translator->trans('grid.gallery_management.title',  array(), $this->translationDomain)
            )
            ->setAutoWidth(true)
            ->setColNames(array(
                'category_id',
                $this->translator->trans('grid.gallery_management.title',  array(), $this->translationDomain),
                $this->translator->trans('grid.gallery_management.slug',  array(), $this->translationDomain),
                $this->translator->trans('grid.gallery_management.category',  array(), $this->translationDomain),
                $this->translator->trans('grid.gallery_management.displayed',  array(), $this->translationDomain),
                $this->translator->trans('grid.gallery_management.enabled',  array(), $this->translationDomain),

            ))
            ->setColModel(array(
                array(
                    'name' => 'c.id', 'index' => 'c.id', 'hidden' => true,
                ), 
                array(
                    'name' => 'g.title', 'index' => 'g.title', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.slug', 'index' => 'c.slug', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.title', 'index' => 'c.title', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.displayed', 'index' => 'c.displayed', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                    'formatter' => 'checkbox',  'search' => true, 'stype' => 'select',
                    'searchoptions' => array('value' => array(
                        1 => $this->translator->trans('grid.enabled', array(), $this->translationDomain), 
                        0 => $this->translator->trans('grid.disabled', array(), $this->translationDomain), 
                    ))
                ), 
                        
                array(
                    'name' => 'c.enabled', 'index' => 'c.enabled',  'width' => 40, 
                    'align' => 'left',  'sortable' => true, 
                    'formatter' => 'checkbox',  'search' => true, 'stype' => 'select',
                    'searchoptions' => array('value' => array(
                        1 => $this->translator->trans('grid.enabled', array(), $this->translationDomain), 
                        0 => $this->translator->trans('grid.disabled', array(), $this->translationDomain), 
                    ))
                ),

            ))
            ->setQueryBuilder($this->manager->getQueryBuilderForGalleryManagementDataGrid())
            ->setSortName('g.title')
            ->setSortOrder('asc')
            ->enablePager(true)
            ->enableViewRecords(true)
            ->enableSearchButton(true)
            ->enableEditButton(true)
            ->setEditBtnUri($this->router->generate('neutron_gallery.backend.gallery.update', array('id' => '{c.id}'), true))
            ->enableDeleteButton(true)
            ->setDeleteBtnUri($this->router->generate('neutron_gallery.backend.gallery.delete', array('id' => '{c.id}'), true))
            ->setFetchJoinCollection(false)
        ;

        return $dataGrid;
    }



}