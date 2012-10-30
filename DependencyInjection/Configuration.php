<?php

namespace Neutron\Plugin\GalleryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neutron_gallery');

        $this->addGeneralConfigurations($rootNode);
        $this->addImageOptionsConfigurations($rootNode);
        $this->addFormBackendConfigurations($rootNode);

        return $treeBuilder;
    }
    
    private function addGeneralConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable')->defaultFalse()->end()
                ->scalarNode('gallery_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('image_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('controller_backend')->defaultValue('neutron_gallery.controller.backend.gallery.default')->end()
                ->scalarNode('controller_frontend')->defaultValue('neutron_gallery.controller.frontend.gallery.default')->end()
                ->scalarNode('manager')->defaultValue('neutron_gallery.doctrine.gallery_manager.default')->end()
                ->scalarNode('datagrid')->defaultValue('neutron_gallery_management')->end()
                ->arrayNode('templates')
                    ->useAttributeAsKey('name')
                        ->prototype('scalar')
                    ->end() 
                    ->cannotBeOverwritten()
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('translation_domain')->defaultValue('NeutronGalleryBundle')->end()
            ->end()
        ;
    }
    
    private function addImageOptionsConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('image_options')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('min_width')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('min_height')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('extensions')->defaultValue('jpeg,jpg')->end()
                            ->scalarNode('max_size')->defaultValue('2M')->end()
                            ->scalarNode('runtimes')->defaultValue('html5,flash')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
    
    private function addFormBackendConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('form_backend')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('type')->defaultValue('neutron_gallery')->end()
                            ->scalarNode('handler')->defaultValue('neutron_gallery.form.backend.handler.gallery.default')->end()
                            ->scalarNode('name')->defaultValue('neutron_gallery')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
