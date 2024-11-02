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
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Service for analyzing user agent strings.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class UserAgentAnalyzer
{
    private ?LoggerInterface $logger;

    private RequestStack $requestStack;

    public function __construct(
        RequestStack $requestStack,
        ?LoggerInterface $logger = null
    ) {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * Analyze the user agent from the current request.
     *
     * @param bool $touchSupportMode Enable touch support mode detection
     *
     * @return UserAgentResult The analysis result
     */
    public function analyzeCurrentRequest(bool $touchSupportMode = false): UserAgentResult
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            $this->logInfo('No current request found, returning empty result');

            return new UserAgentResult();
        }

        $userAgent = $request->headers->get('User-Agent', '');
        $this->logInfo('Analyzing user agent from current request', ['user_agent' => $userAgent]);

        return $this->analyze($userAgent, $touchSupportMode);
    }

    /**
     * Analyze a user agent string and return detailed information.
     *
     * @param string|null $userAgent        The user agent string to analyze, null will be treated as empty string
     * @param bool        $touchSupportMode Enable touch support mode detection
     *
     * @return UserAgentResult The analysis result
     */
    public function analyze(?string $userAgent, bool $touchSupportMode = false): UserAgentResult
    {
        $userAgent ??= '';
        $this->logInfo('Starting user agent analysis', ['user_agent' => $userAgent]);

        $result = new UserAgentResult();

        if ('' === $userAgent) {
            $this->logInfo('Empty user agent string provided, returning default result');

            return $result;
        }

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
