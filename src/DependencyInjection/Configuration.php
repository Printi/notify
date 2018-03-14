<?php

namespace Printi\NotifyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('notify');
        $root
            ->children()
                ->arrayNode('transition')
                    ->children()
                        ->scalarNode('send_to_prepress')->end()
                        ->scalarNode('prepress_reject')->end()
                        ->scalarNode('prepress_reject_failed')->end()
                        ->scalarNode('prepress_approve')->end()
                        ->scalarNode('send_to_production')->end()
                        ->scalarNode('waiting_for_upload')->end()
                        ->scalarNode('new_upload')->end()
                        ->scalarNode('cancel')->end()
                        ->scalarNode('finish')->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
