<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\Detector;

use Eprofos\UserAgentAnalyzerBundle\Model\UserAgentResult;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\MacOSVersionMapper;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\UserAgentMatcher;

/**
 * Detects operating system information from user agent strings.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class OSDetector
{
    private UserAgentMatcher $matcher;

    private MacOSVersionMapper $macOSMapper;

    private UserAgentResult $result;

    private bool $touchSupportMode;

    private bool $osNeedContinue = true;

    public function __construct(UserAgentMatcher $matcher, MacOSVersionMapper $macOSMapper, UserAgentResult $result, bool $touchSupportMode = false)
    {
        $this->matcher = $matcher;
        $this->macOSMapper = $macOSMapper;
        $this->result = $result;
        $this->touchSupportMode = $touchSupportMode;
    }

    /**
     * Detect the operating system.
     */
    public function detect(): void
    {
        $this->detectMobileOS();
        $this->detectDesktopOS();
        $this->detectMixedOS();
        $this->detectDarwinOS();
        $this->detectOtherOS();
        $this->finalizeMacOSDetection();
    }

    /**
     * Detect mobile operating systems.
     */
    private function detectMobileOS(): void
    {
        // Desktop Mode detection
        if ($this->touchSupportMode) {
            if ($this->matcher->match('X11; Linux x86_64|X11; Linux aarch64|X11; U; U; Linux x86_64')
                && ! $this->matcher->match('Qt|Arora|Ubuntu|Debian|Fedora|Linux Mint|elementary OS')) {
                $this->result->setOsName('Android')
                            ->setIsBrowserDesktopMode(true);
            }

            if ($this->matcher->match('Intel Mac OS X')) {
                $this->result->setOsName('iOS')
                            ->setIsBrowserDesktopMode(true);
            }
        }

        $mobileOSList = [
            ['Android', 'Android', '/Android(?: |\-)([0-9]+\.[0-9]+)/', 'android'],
            ['iOS', 'iPhone|iPad|iPod', '/OS\s([0-9_]+)/', 'macintosh'],
            ['Windows Phone', 'Windows Phone|WPDesktop', '/Windows Phone(?: OS)?\s([0-9]+\.[0-9]+)/', 'windows'],
            ['Windows Mobile', 'Windows Mobile|WCE|Windows CE', '/Windows Mobile\s([0-9]+\.[0-9]+)/', 'windows'],
            ['BlackBerry', 'BlackBerry|BB10|RIM Tablet', '/Version\/([0-9]+\.[0-9]+)/', 'blackberry'],
            ['Symbian', 'Symbian|SymbOS|Series60|Series40', '/Symbian(?:OS)?\/([0-9]+\.[0-9]+)/', 'symbian'],
            ['webOS', 'webOS|hpwOS', '/(?:web|hpw)OS\/([0-9]+\.[0-9]+)/', 'linux'],
            ['Firefox OS', 'Firefox OS|FxOS', '/(?:Firefox|FxOS)\/([0-9]+\.[0-9]+)/', 'linux'],
            ['Bada', 'Bada', '/Bada\/([0-9]+\.[0-9]+)/', 'linux'],
            ['Tizen', 'Tizen', '/Tizen\/([0-9]+\.[0-9]+)/', 'linux'],
            ['Sailfish', 'Sailfish', '/Sailfish\s([0-9]+\.[0-9]+)/', 'linux'],
            ['Ubuntu Touch', 'Ubuntu Touch', '/Ubuntu Touch\s([0-9]+\.[0-9]+)/', 'linux'],
            ['Fire OS', 'Kindle Fire|KFTT|KFOT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA', '', 'android'],
            ['MeeGo', 'MeeGo', '/MeeGo\s([0-9]+\.[0-9]+)/', 'linux'],
            ['KaiOS', 'KAIOS', '/KAIOS\/([0-9]+\.[0-9]+)/', 'linux'],
            ['HarmonyOS', 'HarmonyOS', '/HarmonyOS\s([0-9]+\.[0-9]+)/', 'harmony'],
            ['MIUI', 'MIUI', '/MIUI\/([0-9]+\.[0-9]+)/', 'android'],
            ['EMUI', 'EMUI', '/EMUI\s([0-9]+\.[0-9]+)/', 'android'],
            ['ColorOS', 'ColorOS', '/ColorOS\/([0-9]+\.[0-9]+)/', 'android'],
            ['OxygenOS', 'OxygenOS', '/OxygenOS\/([0-9]+\.[0-9]+)/', 'android'],
            ['FuntouchOS', 'FuntouchOS', '/FuntouchOS\/([0-9]+\.[0-9]+)/', 'android'],
            ['RealmeUI', 'RealmeUI', '/RealmeUI\/([0-9]+\.[0-9]+)/', 'android'],
            ['One UI', 'One UI', '/One UI\s([0-9]+\.[0-9]+)/', 'android'],
        ];

        foreach ($mobileOSList as [$name, $match, $versionPattern, $family]) {
            if ($this->matcher->match($match)) {
                $this->result->setOsName($name)
                            ->setOsFamily($family)
                            ->setOsType('mobile');

                if ('' !== $versionPattern) {
                    $matches = $this->matcher->match($versionPattern);
                    if (\is_array($matches) && ! empty($matches[1])) {
                        $version = (string) $matches[1];
                        $version = str_replace('_', '.', $version);
                        $this->result->setOsVersion((float) $version);
                    }
                }

                if ('iOS' === $name) {
                    $this->result->setIsResultIos(true);
                }

                $this->osNeedContinue = false;

                break;
            }
        }
    }

    /**
     * Detect desktop operating systems.
     */
    private function detectDesktopOS(): void
    {
        if (! $this->osNeedContinue) {
            return;
        }

        $windowsVersions = [
            'NT 11.0' => 11.0,
            'NT 10.1' => 11.0,
            'NT 10.0' => 10.0,
            'NT 6.4' => 10.0,
            'NT 6.3' => 8.1,
            'NT 6.2' => 8.0,
            'NT 6.1' => 7.0,
            'NT 6.0' => 6.0,
            'NT 5.2' => 5.2,
            'NT 5.1' => 5.1,
            'NT 5.01' => 5.01,
            'NT 5.0' => 5.0,
            'NT 4.0' => 4.0,
            '98' => 98.0,
            '95' => 95.0,
            'ME' => 4.9,
        ];

        $desktopOSList = [
            ['Windows', 'Windows NT|Win32|WinNT', $windowsVersions, 'windows'],
            ['Linux Mint', 'Linux Mint', '/Linux Mint\/([0-9.]+)/', 'linux'],
            ['Ubuntu', 'Ubuntu', '/Ubuntu\/([0-9.]+)/', 'linux'],
            ['Debian', 'Debian', '/Debian\/([0-9.]+)/', 'linux'],
            ['Fedora', 'Fedora', '/Fedora\/([0-9.]+)/', 'linux'],
            ['Red Hat', 'Red Hat', '/Red Hat\/([0-9.]+)/', 'linux'],
            ['SUSE', 'SUSE', '/SUSE\/([0-9.]+)/', 'linux'],
            ['Arch Linux', 'Arch Linux', '/Arch Linux\/([0-9.]+)/', 'linux'],
            ['Gentoo', 'Gentoo', '/Gentoo\/([0-9.]+)/', 'linux'],
            ['elementary OS', 'elementary OS', '/elementary OS\/([0-9.]+)/', 'linux'],
            ['Manjaro', 'Manjaro', '/Manjaro\/([0-9.]+)/', 'linux'],
            ['CentOS', 'CentOS', '/CentOS\/([0-9.]+)/', 'linux'],
            ['PCLinuxOS', 'PCLinuxOS', '/PCLinuxOS\/([0-9.]+)/', 'linux'],
            ['Mageia', 'Mageia', '/Mageia\/([0-9.]+)/', 'linux'],
            ['Slackware', 'Slackware', '/Slackware\/([0-9.]+)/', 'linux'],
            ['FreeBSD', 'FreeBSD', '/FreeBSD\/([0-9.]+)/', 'unix'],
            ['OpenBSD', 'OpenBSD', '/OpenBSD\/([0-9.]+)/', 'unix'],
            ['NetBSD', 'NetBSD', '/NetBSD\/([0-9.]+)/', 'unix'],
            ['DragonFly', 'DragonFly', '/DragonFly\/([0-9.]+)/', 'unix'],
            ['Solaris', 'Solaris|SunOS', '/Solaris|SunOS\/([0-9.]+)/', 'unix'],
            ['IRIX', 'IRIX', '/IRIX\/([0-9.]+)/', 'unix'],
            ['HP-UX', 'HP-UX', '/HP-UX\/([0-9.]+)/', 'unix'],
            ['AIX', 'AIX', '/AIX\/([0-9.]+)/', 'unix'],
            ['OS/2', 'OS/2', '/OS\/2\/([0-9.]+)/', 'os2'],
            ['BeOS', 'BeOS', '/BeOS\/([0-9.]+)/', 'beos'],
            ['Haiku', 'Haiku', '/Haiku\/([0-9.]+)/', 'beos'],
            ['AmigaOS', 'AmigaOS', '/AmigaOS\/([0-9.]+)/', 'amiga'],
            ['MorphOS', 'MorphOS', '/MorphOS\/([0-9.]+)/', 'amiga'],
            ['RISC OS', 'RISC OS', '/RISC OS\/([0-9.]+)/', 'risc'],
            ['Plan 9', 'Plan 9', '/Plan 9\/([0-9.]+)/', 'plan9'],
            ['QNX', 'QNX', '/QNX\/([0-9.]+)/', 'qnx'],
            ['Chrome OS', 'CrOS|ChromiumOS', '/Chrome OS\/([0-9.]+)/', 'linux'],
        ];

        foreach ($desktopOSList as [$name, $match, $versionInfo, $family]) {
            if ($this->matcher->match($match)) {
                $this->result->setOsName($name)
                            ->setOsFamily($family)
                            ->setOsType('desktop');

                if (is_array($versionInfo)) {
                    // Windows version mapping
                    $matches = $this->matcher->match('/Windows ([ .a-zA-Z0-9]+)[;\\)]/');
                    $version = \is_array($matches) ? $matches[1] : '';
                    if (isset($versionInfo[$version])) {
                        $this->result->setOsVersion($versionInfo[$version]);
                    }
                } elseif (is_string($versionInfo) && '' !== $versionInfo) {
                    $matches = $this->matcher->match($versionInfo);
                    if (\is_array($matches) && ! empty($matches[1])) {
                        $this->result->setOsVersion((float) $matches[1]);
                    }
                }

                $this->osNeedContinue = false;

                break;
            }
        }
    }

    /**
     * Detect mixed operating systems.
     */
    private function detectMixedOS(): void
    {
        if (! $this->osNeedContinue) {
            return;
        }

        $mixedOSList = [
            ['WebOS', ['hpwOS', 'Web0S', 'WebOS', 'webOS'], 'linux'],
            ['ChromiumOS', ['ChromiumOS', 'CrOS'], 'linux'],
            ['RemixOS', ['RemixOS', 'Remix Mini'], 'android'],
            ['Tizen', ['Tizen'], 'linux'],
            ['Sailfish OS', ['Sailfish'], 'linux'],
            ['PlayStation OS', ['PlayStation', 'PLAYSTATION'], 'unix'],
            ['Firefox OS', ['Firefox OS', 'FxOS'], 'linux'],
            ['SteamOS', ['SteamOS'], 'linux'],
        ];

        foreach ($mixedOSList as [$name, $patterns, $family]) {
            foreach ($patterns as $pattern) {
                if ($this->matcher->match($pattern)) {
                    $this->result->setOsName($name)
                                ->setOsFamily($family)
                                ->setOsType('mixed')
                                ->setOsVersion(0.0);
                    $this->osNeedContinue = false;

                    break 2;
                }
            }
        }
    }

    /**
     * Detect Darwin-based operating systems.
     */
    private function detectDarwinOS(): void
    {
        if (! $this->osNeedContinue || ! $this->matcher->match(' Darwin') || $this->matcher->match('X11;')) {
            return;
        }

        $this->result->setOsFamily('macintosh');

        if ($this->matcher->match('x86_64|i386|Mac')) {
            $this->result->setOsType('desktop')
                        ->setOsName('MacOS');
        } else {
            $this->result->setOsType('mobile')
                        ->setOsName('iOS')
                        ->setIsResultIos(true);
        }

        $matches = $this->matcher->match('/\sDarwin(\s|\/)([0-9]+\.[0-9]+)/');
        if (\is_array($matches) && ! empty($matches[2])) {
            $darwinVersion = (float) $matches[2];
            $versionMap = 'MacOS' === $this->result->getOsName() ?
                         $this->macOSMapper->getDarwinMacOSMap() :
                         $this->macOSMapper->getDarwinIOSMap();

            // Find closest Darwin version
            $minDiff = PHP_FLOAT_MAX;
            $matchedVersion = null;
            foreach ($versionMap as $k => $v) {
                $diff = abs((float) $k - $darwinVersion);
                if ($diff < $minDiff) {
                    $minDiff = $diff;
                    $matchedVersion = $v;
                }
            }

            if (null !== $matchedVersion) {
                $this->result->setOsVersion((float) $matchedVersion);
            }
        }
    }

    /**
     * Detect other operating systems.
     */
    private function detectOtherOS(): void
    {
        if (! $this->osNeedContinue) {
            return;
        }

        $otherOSList = [
            ['Smart TV', 'SmartTV|SMART-TV|SmartHub|Smart Hub|Opera TV|WebTV', 'tv'],
            ['Nintendo Switch', 'Nintendo Switch', 'game'],
            ['PlayStation', 'PlayStation|PS4|PS5|PSP|PS Vita', 'game'],
            ['Xbox', 'Xbox|XBOX', 'game'],
            ['Car System', 'CarBrowser|Tesla|TESLAOS', 'car'],
            ['Smart Watch', 'Watch|Apple Watch|Galaxy Watch|WATCH', 'watch'],
        ];

        foreach ($otherOSList as [$name, $match, $type]) {
            if ($this->matcher->match($match)) {
                $this->result->setOsName($name)
                            ->setOsFamily('other')
                            ->setOsType($type)
                            ->setOsVersion(0.0);
                $this->osNeedContinue = false;

                break;
            }
        }
    }

    /**
     * Finalize MacOS detection with special cases.
     */
    private function finalizeMacOSDetection(): void
    {
        if ('MacOS' === $this->result->getOsName()
            && 15 === $this->result->getMacosVersionMinor()
            && 'Firefox' === $this->result->getBrowserName()
            && $this->result->getBrowserVersion() > 86) {
            $this->result->setOsVersion(16.0)
                        ->setOsTitle('MacOS Big Sur');
        }
    }
}
