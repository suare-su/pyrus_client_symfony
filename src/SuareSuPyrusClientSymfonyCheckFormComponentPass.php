<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @psalm-api
 */
final class SuareSuPyrusClientSymfonyCheckFormComponentPass implements CompilerPassInterface
{
    public function __construct(private readonly string $additionalServices)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('form.factory')) {
            return;
        }

        $loader = new YamlFileLoader($container, new FileLocator(\dirname($this->additionalServices)));
        $loader->load(pathinfo($this->additionalServices, \PATHINFO_BASENAME));
    }
}
