<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\Detector;

use Eprofos\UserAgentAnalyzerBundle\Model\UserAgentResult;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\UserAgentMatcher;

/**
 * Detects device information from user agent strings.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class DeviceDetector
{
    private UserAgentMatcher $matcher;

    private UserAgentResult $result;

    public function __construct(UserAgentMatcher $matcher, UserAgentResult $result)
    {
        $this->matcher = $matcher;
        $this->result = $result;
    }

    /**
     * Detect the device type.
     */
    public function detect(): void
    {
        $this->detectDesktop();
        $this->detectMobile();
        $this->detectTV();
        $this->detectConsole();
        $this->detectMediaPlayer();
        $this->detectCar();
        $this->detectWatch();
    }

    /**
     * Detect desktop devices.
     */
    private function detectDesktop(): void
    {
        if ('unknown' !== $this->result->getOsFamily() && 'mixed' !== $this->result->getOsType()) {
            $this->result->setDeviceType('desktop');
        }
    }

    /**
     * Detect mobile devices.
     */
    private function detectMobile(): void
    {
        if ($this->matcher->match('Mobile|Android|Tablet|iPad|iPhone|iPod|webOS|BlackBerry|Windows Phone')) {
            $this->result->setDeviceType('mobile');
        }
    }

    /**
     * Detect TV devices.
     */
    private function detectTV(): void
    {
        if ($this->matcher->match('TV|HDMI|CrKey| Escape |Kylo/|SmartLabs|SC/IHD|Viera|BRAVIA|NetCast|Roku/DVP| Roku |Maple|DuneHD|CE-HTML|EIS iPanel|Sunniwell; Resolution|Freebox|Netbox|Netgem|AFTT|AFTM|AFTB|DLNADOC| iconBIT |olleh tv|stbapp |; MIBOX|ABOX-I|; H96 |; X96|HX S905|; M8S |MINIM8S|MXIII-|; NEO-X|; NEO-U| Nexus Player |TPM171E|; V88 |MXQPRO|NEXBOX-|; Leelbox|ZIDOO|; A95X| Beelink |; T95Z|; TX3 ')) {
            $this->result->setDeviceType('tv');
        }
    }

    /**
     * Detect gaming consoles.
     */
    private function detectConsole(): void
    {
        if ($this->matcher->matchCaseInsensitive('playstation')
            || $this->matcher->match('Xbox|GAMEPAD|Nintendo|OUYA|; SHIELD Build')) {
            $this->result->setDeviceType('console');
        }
    }

    /**
     * Detect media players.
     */
    private function detectMediaPlayer(): void
    {
        if ($this->matcher->match('iPod|AlexaMediaPlayer|AppleCoreMedia')) {
            $this->result->setDeviceType('mediaplayer');
        }
    }

    /**
     * Detect car systems.
     */
    private function detectCar(): void
    {
        if ($this->matcher->match('CarBrowser|Tesla/')) {
            $this->result->setDeviceType('car');
        }
    }

    /**
     * Detect smartwatches.
     */
    private function detectWatch(): void
    {
        if ($this->matcher->matchCaseInsensitive('watch') && ! $this->matcher->match('AirWatch')) {
            $this->result->setDeviceType('watch');
        }
    }
}
