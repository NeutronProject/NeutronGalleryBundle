<?php
namespace Neutron\Plugin\GalleryBundle;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Neutron\MvcBundle\Plugin\PluginFactoryInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Routing\RouterInterface;

use Neutron\MvcBundle\MvcEvents;

use Neutron\MvcBundle\Event\ConfigurePluginEvent;

class GalleryPlugin
{
    const IDENTIFIER = 'neutron.plugin.gallery';
    
    protected $dispatcher;
    
    protected $factory;
    
    protected $router;
    
    protected $translator;
    
    protected $translationDomain;
    
    public function __construct(EventDispatcher $dispatcher, PluginFactoryInterface $factory, 
            RouterInterface $router, TranslatorInterface $translator, $translationDomain)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
        
    }
    
    public function build()
    {
        $plugin = $this->factory->createPlugin(self::IDENTIFIER);
        $plugin
            ->setLabel($this->translator->trans('plugin.gallery.label', array(), $this->translationDomain))
            ->setDescription($this->translator->trans('plugin.gallery.description', array(), $this->translationDomain))
            ->setFrontendRoute('neutron_gallery.frontend.gallery')
            ->setUpdateRoute('neutron_gallery.backend.gallery.update')
            ->setDeleteRoute('neutron_gallery.backend.gallery.delete')
            ->setManagerServiceId('neutron_gallery.gallery_manager')
            ->addBackendPage(array(
                'name'      => 'plugin.gallery.management',
                'label'     => 'plugin.gallery.management.label',
                'route'     => 'neutron_gallery.backend.gallery',
                'displayed' => true
            ))
            ->setTreeOptions(array(
                'children_strategy' => 'self',
            ))
        ;
        
        $this->dispatcher->dispatch(
            MvcEvents::onPluginConfigure, 
            new ConfigurePluginEvent($this->factory, $plugin)
        );
        
        return $plugin;
    }
    
}