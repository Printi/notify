<?php

namespace Printi\NotifyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class NotifyExtension extends Extension
{

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);
        $this->mergeConfigParameter($container, 'notify', $config);
    }


    protected function mergeConfigParameter(ContainerBuilder $container, $key, $configs)
    {
        if (!is_array($configs)) {
            $container->setParameter($key, $configs);
            return;
        }

        foreach ($configs as $configKey => $config) {
            $this->mergeConfigParameter($container, "{$key}.{$configKey}", $config);
        }
    }

}
