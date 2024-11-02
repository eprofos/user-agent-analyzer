<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * EprofosUserAgentAnalyzerBundle.
 *
 * A Symfony bundle for user agent detection and analysis.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class EprofosUserAgentAnalyzerBundle extends AbstractBundle
{
    /**
     * @param array<string, mixed> $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Load services
        $container->import('../config/services.yaml');
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
