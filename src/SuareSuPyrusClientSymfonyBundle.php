<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Bundle class.
 *
 * @psalm-api
 */
class SuareSuPyrusClientSymfonyBundle extends AbstractBundle
{
    private const BUNDLE_ID = 'suare_su_pyrus_client_symfony';
    private const SERVICES_YAML = __DIR__ . '/../config/services.yaml';
    private const FORM_CONVERTER_SERVICES_YAML = __DIR__ . '/../config/form_converter_services.yaml';

    /**
     * {@inheritdoc}
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->booleanNode('ignore_unknown_types')->defaultFalse()->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(self::SERVICES_YAML);

        $container->parameters()->set(self::BUNDLE_ID . '.ignore_unknown_types', $config['ignore_unknown_types']);
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(
            new SuareSuPyrusClientSymfonyCheckFormComponentPass(self::FORM_CONVERTER_SERVICES_YAML)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
