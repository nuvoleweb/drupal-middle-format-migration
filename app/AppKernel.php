<?php

namespace NuvoleWeb;

use NuvoleWeb\DrupalMigration\DependencyInjection\CompilerPass\CollectProcessorsCompilerPass;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use NuvoleWeb\DrupalMigration\DependencyInjection\CompilerPass\CollectCommandsCompilerPass;

final class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/../config/services.yml');

        if (file_exists(__DIR__.'/../parameters.yml')) {
            $loader->load(__DIR__.'/../parameters.yml');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function build(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addCompilerPass(new CollectCommandsCompilerPass());
        $containerBuilder->addCompilerPass(new CollectProcessorsCompilerPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return __DIR__ . '/../var/cache/' . $this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return __DIR__ . '/../var/logs';
    }
}
