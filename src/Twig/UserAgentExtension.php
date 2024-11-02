<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Twig;

use Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension providing functions to analyze user agent.
 */
final class UserAgentExtension extends AbstractExtension
{
    public function __construct(
        private readonly UserAgentAnalyzer $userAgentAnalyzer,
    ) {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_mobile', [$this, 'isMobile']),
            new TwigFunction('is_desktop', [$this, 'isDesktop']),
            new TwigFunction('is_tablet', [$this, 'isTablet']),
            new TwigFunction('is_android', [$this, 'isAndroid']),
            new TwigFunction('is_windows', [$this, 'isWindows']),
            new TwigFunction('is_linux', [$this, 'isLinux']),
            new TwigFunction('is_ios', [$this, 'isIOS']),
            new TwigFunction('is_macos', [$this, 'isMacOS']),
        ];
    }

    /**
     * Check if the current device is mobile.
     */
    public function isMobile(): bool
    {
        return $this->userAgentAnalyzer->analyzeCurrentRequest()->isMobile();
    }

    /**
     * Check if the current device is desktop.
     */
    public function isDesktop(): bool
    {
        return $this->userAgentAnalyzer->analyzeCurrentRequest()->isDesktop();
    }

    /**
     * Check if the current device is tablet.
     */
    public function isTablet(): bool
    {
        return $this->userAgentAnalyzer->analyzeCurrentRequest()->isTablet();
    }

    /**
     * Check if the current OS is Android.
     */
    public function isAndroid(): bool
    {
        $result = $this->userAgentAnalyzer->analyzeCurrentRequest();

        return 'android' === strtolower($result->getOsName());
    }

    /**
     * Check if the current OS is Windows.
     */
    public function isWindows(): bool
    {
        $result = $this->userAgentAnalyzer->analyzeCurrentRequest();

        return 'windows' === strtolower($result->getOsName());
    }

    /**
     * Check if the current OS is Linux.
     */
    public function isLinux(): bool
    {
        $result = $this->userAgentAnalyzer->analyzeCurrentRequest();

        return 'linux' === strtolower($result->getOsName());
    }

    /**
     * Check if the current OS is iOS.
     */
    public function isIOS(): bool
    {
        $result = $this->userAgentAnalyzer->analyzeCurrentRequest();

        return 'ios' === strtolower($result->getOsName());
    }

    /**
     * Check if the current OS is macOS.
     */
    public function isMacOS(): bool
    {
        $result = $this->userAgentAnalyzer->analyzeCurrentRequest();

        return 'macos' === strtolower($result->getOsName());
    }
}
