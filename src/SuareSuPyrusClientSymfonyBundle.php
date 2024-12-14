<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Bundle object.
 *
 * @psalm-api
 */
class SuareSuPyrusClientSymfonyBundle extends AbstractBundle
{
    private const SERVICES_YAML = '../config/services.yaml';
    private const FORM_CONVERTER_SERVICES_YAML = '../config/form_converter_services.yaml';

    /**
     * {@inheritdoc}
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(self::SERVICES_YAML);
        if ($builder->has('form.factory')) {
            $container->import(self::FORM_CONVERTER_SERVICES_YAML);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
