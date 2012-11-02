<?php

namespace Neutron\Plugin\GalleryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NeutronGalleryExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->loadGeneralConfigurations($config, $container);
    }
    
    protected function loadGeneralConfigurations(array $config, ContainerBuilder $container)
    {
        if ($config['enable'] === false){
            $container->getDefinition('neutron_gallery.plugin')
                ->clearTag('neutron.plugin');
        }
        
        $container->setParameter('neutron_gallery.gallery_class', $config['gallery_class']);
        $container->setParameter('neutron_gallery.image_class', $config['image_class']);
        $container->setAlias('neutron_gallery.gallery_manager', $config['manager']);
        $container->setParameter('neutron_gallery.datagrid.gallery_management', $config['datagrid']);
        $container->setAlias('neutron_gallery.controller.backend.gallery', $config['controller_backend']);
        $container->setAlias('neutron_gallery.controller.frontend.gallery', $config['controller_frontend']);
        
        $container->setAlias('neutron_gallery.form.backend.handler.gallery', $config['form_backend']['handler']);
        $container->setParameter('neutron_gallery.form.backend.type.gallery', $config['form_backend']['type']);
        $container->setParameter('neutron_gallery.form.backend.name.gallery', $config['form_backend']['name']);
        
        $container->setParameter('neutron_gallery.templates', $config['templates']);
        $container->setParameter('neutron_gallery.image_options', $config['image_options']);
        $container->setParameter('neutron_gallery.translation_domain', $config['translation_domain']);
    }

}
