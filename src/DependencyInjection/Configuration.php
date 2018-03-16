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
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                ->end()
            ->end();

        return $builder;
    }
}
