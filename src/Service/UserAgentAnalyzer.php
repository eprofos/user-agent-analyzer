<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Service;

use Eprofos\UserAgentAnalyzerBundle\Model\UserAgentResult;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\Detector\BrowserDetector;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\Detector\DeviceDetector;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\Detector\OSDetector;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\MacOSVersionMapper;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\UserAgentMatcher;
use Psr\Log\LoggerInterface;

/**
 * Service for analyzing user agent strings.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class UserAgentAnalyzer
{
    private ?LoggerInterface $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Analyze a user agent string and return detailed information.
     *
     * @param string $userAgent        The user agent string to analyze
     * @param bool   $touchSupportMode Enable touch support mode detection
     *
     * @return UserAgentResult The analysis result
     */
    public function analyze(string $userAgent, bool $touchSupportMode = false): UserAgentResult
    {
        $this->logInfo('Starting user agent analysis', ['user_agent' => $userAgent]);

        $result = new UserAgentResult();
        $matcher = new UserAgentMatcher($userAgent);
        $macOSMapper = new MacOSVersionMapper();

        // Detect operating system
        $osDetector = new OSDetector($matcher, $macOSMapper, $result, $touchSupportMode);
        $osDetector->detect();
        $this->logInfo('OS detection completed', ['os' => $result->getOsName()]);

        // Detect browser
        $browserDetector = new BrowserDetector($matcher, $result);
        $browserDetector->detect();
        $this->logInfo('Browser detection completed', ['browser' => $result->getBrowserName()]);

        // Detect device
        $deviceDetector = new DeviceDetector($matcher, $result);
        $deviceDetector->detect();
        $this->logInfo('Device detection completed', ['device' => $result->getDeviceType()]);

        // Set OS title
        if (! empty($result->getOsVersion())) {
            $result->setOsTitle($result->getOsName().' '.$result->getOsVersion());
        } else {
            $result->setOsTitle($result->getOsName());
        }

        $this->logInfo('User agent analysis completed', [
            'os' => $result->getOsTitle(),
            'browser' => $result->getBrowserTitle(),
            'device' => $result->getDeviceType(),
        ]);

        return $result;
    }

    /**
     * Log info message if logger is available.
     *
     * @param array<string, mixed> $context
     */
    private function logInfo(string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->info('[UserAgentAnalyzerBundle] '.$message, $context);
        }
    }
}
