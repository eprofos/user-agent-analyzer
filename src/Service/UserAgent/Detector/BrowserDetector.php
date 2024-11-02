<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\Detector;

use Eprofos\UserAgentAnalyzerBundle\Model\UserAgentResult;
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\UserAgentMatcher;

/**
 * Detects browser information from user agent strings.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class BrowserDetector
{
    private UserAgentMatcher $matcher;

    private UserAgentResult $result;

    private bool $browserNeedContinue = true;

    public function __construct(UserAgentMatcher $matcher, UserAgentResult $result)
    {
        $this->matcher = $matcher;
        $this->result = $result;
    }

    /**
     * Detect the browser.
     */
    public function detect(): void
    {
        // Detect Edge first as it contains Chrome in its UA
        $this->detectEdge();

        if ($this->browserNeedContinue) {
            $this->detectChromium();
            $this->detectFirefox();
            $this->detectCrossDeviceBrowsers();
            $this->detectDesktopBrowsers();
            $this->detectMobileBrowsers();
            $this->detectWebView();
            $this->detectSafariOriginal();
        }

        $this->finalizeBrowserDetection();
    }

    /**
     * Detect Edge browser specifically.
     */
    private function detectEdge(): void
    {
        if ($this->matcher->match('Edg/')) {
            $matches = $this->matcher->match('/Edg\/([0-9]+)\./');

            $this->result->setBrowserName('Edge')
                        ->setBrowserVersion(! empty($matches[1]) ? (float) $matches[1] : 0)
                        ->setBrowserChromiumVersion(! empty($matches[1]) ? (int) $matches[1] : 0);

            $this->browserNeedContinue = false;
        }
    }

    /**
     * Detect Chromium-based browsers.
     */
    private function detectChromium(): void
    {
        if ($this->matcher->match('Chrome/|Chromium/|CriOS/|CrMo/|EdgA/|EdgiOS/|OPR/|OPT/')) {
            $matches = $this->matcher->match('/(Chrome|Chromium|CriOS|CrMo|EdgA|EdgiOS|OPR|OPT)\/([0-9]+)\./');

            $browserName = 'Chrome';
            if (! empty($matches[1])) {
                switch ($matches[1]) {
                    case 'Chromium':
                        $browserName = 'Chromium';

                        break;
                    case 'EdgA':
                    case 'EdgiOS':
                        $browserName = 'Edge';

                        break;
                    case 'OPR':
                    case 'OPT':
                        $browserName = 'Opera';

                        break;
                }
            }

            $this->result->setBrowserName($browserName)
                        ->setBrowserVersion(! empty($matches[2]) ? (float) $matches[2] : 0)
                        ->setBrowserChromiumVersion((int) $this->result->getBrowserVersion());

            if ($this->matcher->match('CriOS/|EdgiOS/|OPT/')) {
                $this->result->setBrowserChromiumVersion(0);
            }

            if ($this->matcher->match('/Gecko\)\s(Chrome|CrMo)\/(\d+\.\d+\.\d+\.\d+)\s(?:Mobile)?(?:\/[.0-9A-Za-z]+\s|\s)?Safari\/[.0-9]+$/')
                && ! $this->matcher->match('SalamWeb|Valve|Vivaldi|Brave|Edge|Opera|YaBrowser')) {
                $this->result->setIsBrowserChromeOriginal(true);
            }

            $this->browserNeedContinue = false;
        }
    }

    /**
     * Detect Firefox browser and its variants.
     */
    private function detectFirefox(): void
    {
        if ($this->browserNeedContinue
            && 0 === $this->result->getBrowserChromiumVersion()
            && $this->matcher->match('Firefox|FxiOS|Focus|Waterfox|IceCat|Pale Moon|Basilisk')) {

            $browserName = 'Firefox';
            $versionPattern = '/Firefox\/([0-9]+)\./';

            if ($this->matcher->match('FxiOS')) {
                $browserName = 'Firefox iOS';
                $versionPattern = '/FxiOS\/([0-9]+)\./';
            } elseif ($this->matcher->match('Focus')) {
                $browserName = 'Firefox Focus';
                $versionPattern = '/Focus\/([0-9]+)\./';
            } elseif ($this->matcher->match('Waterfox')) {
                $browserName = 'Waterfox';
                $versionPattern = '/Waterfox\/([0-9]+)\./';
            } elseif ($this->matcher->match('IceCat')) {
                $browserName = 'GNU IceCat';
                $versionPattern = '/IceCat\/([0-9]+)\./';
            } elseif ($this->matcher->match('PaleMoon')) {
                $browserName = 'Pale Moon';
                $versionPattern = '/PaleMoon\/([0-9]+)\./';
            } elseif ($this->matcher->match('Basilisk')) {
                $browserName = 'Basilisk';
                $versionPattern = '/Basilisk\/([0-9]+)\./';
            }

            $matches = $this->matcher->match($versionPattern);

            $this->result->setBrowserName($browserName)
                        ->setBrowserVersion(! empty($matches[1]) ? (float) $matches[1] : 0);

            if ('Firefox' === $browserName && $this->matcher->match('/\)\sGecko\/[.0-9]+\sFirefox\/[.0-9]+$/')) {
                $this->result->setIsBrowserFirefoxOriginal(true);
            }

            $this->browserNeedContinue = false;
        }
    }

    /**
     * Detect cross-device browsers.
     */
    private function detectCrossDeviceBrowsers(): void
    {
        if (! $this->browserNeedContinue
            || $this->result->isBrowserChromeOriginal()
            || $this->result->isBrowserFirefoxOriginal()) {
            return;
        }

        $browserList = [
            ['Yandex Browser', 'YaBrowser', '/YaBrowser\/([0-9]+\.[0-9]+)/', '1', 'YaApp_'],
            ['Edge', 'Edg', '/Edg(|e|A|iOS)\/([0-9]+)\./', '2', ''],
            ['Opera', ' OPR/', '/OPR\/(\d+)/', '1', 'Opera Mini|OPiOS|OPT/|OPRGX/|AlohaBrowser'],
            ['Opera GX', 'OPRGX', '/OPRGX\/([0-9]+)/', '1', ''],
            ['Opera', 'Opera', '/Opera.*Version\/([0-9]+\.[0-9]+)/', '1', 'Opera Mini|OPiOS|OPT/|InettvBrowser/'],
            ['UC Browser', 'UBrowser|UCBrowser|UCMini', '/(UBrowser|UCBrowser|UCMini)\/([0-9]+\.[0-9]+)/', '2', 'UCTurbo|AliApp'],
            ['Vivaldi', 'Vivaldi/', '/Vivaldi\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Brave', 'Brave', '/Brave(?: Chrome)?\/([0-9]+)/', '1', ''],
            ['QQ Browser', 'QQBrowser', '/QQBrowser\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Maxthon', 'Maxthon', '/Maxthon[\/\s]([0-9]+\.[0-9]+)/', '1', ''],
            ['Samsung Browser', 'SamsungBrowser', '/SamsungBrowser\/([0-9]+\.[0-9]+)/', '1', ''],
            ['MIUI Browser', 'MiuiBrowser', '/MiuiBrowser\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Huawei Browser', 'HuaweiBrowser', '/HuaweiBrowser\/([0-9]+)/', '1', ''],
            ['Dolphin', 'Dolphin', '/Dolphin\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Puffin', 'Puffin', '/Puffin\/([0-9]+\.[0-9]+)/', '1', ''],
            ['DuckDuckGo', 'DuckDuckGo', '/DuckDuckGo\/([0-9]+)/', '1', ''],
            ['Ecosia', 'Ecosia', '/Ecosia\sandroid@([0-9]+\.[0-9]+)/', '1', ''],
            ['Whale', 'Whale', '/Whale\/([0-9]+\.[0-9]+)/', '1', ''],
            ['CM Browser', 'CMBrowser', '/CMBrowser\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Tor Browser', 'Tor', '/Tor\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Epic Browser', 'Epic', '/Epic\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Chromodo', 'Chromodo', '/Chromodo\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Iridium', 'Iridium', '/Iridium\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Pale Moon', 'PaleMoon', '/PaleMoon\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Basilisk', 'Basilisk', '/Basilisk\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Fennec', 'Fennec', '/Fennec\/([0-9]+\.[0-9]+)/', '1', ''],
            ['K-Meleon', 'K-Meleon', '/K-Meleon\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Thunderbird', 'Thunderbird', '/Thunderbird\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Seamonkey', 'Seamonkey', '/Seamonkey\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Konqueror', 'Konqueror', '/Konqueror\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Falkon', 'Falkon', '/Falkon\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Midori', 'Midori', '/Midori\/([0-9]+\.[0-9]+)/', '1', ''],
        ];

        foreach ($browserList as [$name, $match, $versionPattern, $versionIndex, $exclude]) {
            if ($this->matcher->match($match) && ! $this->matcher->match($exclude)) {
                $matches = $this->matcher->match($versionPattern);

                $this->result->setBrowserName($name)
                            ->setBrowserVersion(! empty($matches[(int) $versionIndex]) ? (float) $matches[(int) $versionIndex] : 0);

                if (! empty($this->result->getBrowserVersion())) {
                    $this->browserNeedContinue = false;

                    break;
                }
            }
        }
    }

    /**
     * Detect desktop browsers.
     */
    private function detectDesktopBrowsers(): void
    {
        if (! $this->browserNeedContinue) {
            return;
        }

        $browserList = [
            ['Safari', '/AppleWebKit\/[.0-9]+.*Gecko\)\sSafari\/[.0-9A-Za-z]+$/', '/Safari\/(\d+)/', '1', 'Version/'],
            ['Safari', '/Version\/([0-9]+\.[0-9]+).*Safari/', '/Version\/([0-9]+\.[0-9]+).*Safari/', '1', 'Epiphany|Arora/|Midori|midori|SlimBoat'],
            ['Internet Explorer', 'MSIE', '/MSIE(?:\s|)([0-9]+)/', '1', 'Opera|IEMobile|Trident/'],
            ['Internet Explorer', 'Trident/', '/Trident\/([0-9]+)/', '1', 'Opera|IEMobile'],
            ['Chromium', 'Chromium', '/Chromium\/([0-9]+\.[0-9]+)/', '1', ''],
            ['SeaMonkey', 'SeaMonkey', '/SeaMonkey\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Opera', 'Opera', '/Opera\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Netscape', 'Netscape', '/Netscape\/([0-9]+\.[0-9]+)/', '1', ''],
            ['OmniWeb', 'OmniWeb', '/OmniWeb\/([0-9]+\.[0-9]+)/', '1', ''],
            ['iCab', 'iCab', '/iCab\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Camino', 'Camino', '/Camino\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Galeon', 'Galeon', '/Galeon\/([0-9]+\.[0-9]+)/', '1', ''],
            ['Epiphany', 'Epiphany', '/Epiphany\/([0-9]+\.[0-9]+)/', '1', ''],
        ];

        foreach ($browserList as [$name, $match, $versionPattern, $versionIndex, $exclude]) {
            if ($this->matcher->match($match) && ! $this->matcher->match($exclude)) {
                $matches = $this->matcher->match($versionPattern);

                $this->result->setBrowserName($name)
                            ->setBrowserVersion(! empty($matches[(int) $versionIndex]) ? (float) $matches[(int) $versionIndex] : 0);

                // Safari old version conversion
                if ('Safari' === $name && '/AppleWebKit\/[.0-9]+.*Gecko\)\sSafari\/[.0-9A-Za-z]+$/' === $match) {
                    $ev = (int) $this->result->getBrowserVersion();
                    if (! empty($ev)) {
                        $this->result->setBrowserVersion(1.0);
                    }
                    if ($ev > 400) {
                        $this->result->setBrowserVersion(2.0);
                    }
                    if ($ev > 500) {
                        $this->result->setBrowserVersion(3.0);
                    }
                    if ($ev > 527) {
                        $this->result->setBrowserVersion(4.0);
                    }
                    if ($ev > 532) {
                        $this->result->setBrowserVersion(5.0);
                    }
                    if ($ev > 535) {
                        $this->result->setBrowserVersion(6.0);
                    }
                    $this->result->setIsBrowserSafariOriginal(true);
                }

                // IE Trident engine version conversion
                if ('Internet Explorer' === $name && 'Trident/' === $match) {
                    $tridentVersionMap = [
                        4 => 8.0,
                        5 => 9.0,
                        6 => 10.0,
                        7 => 11.0,
                    ];

                    $version = (int) $this->result->getBrowserVersion();
                    if (isset($tridentVersionMap[$version])) {
                        $this->result->setBrowserVersion($tridentVersionMap[$version]);
                    }
                }

                if (! empty($this->result->getBrowserVersion())) {
                    $this->browserNeedContinue = false;

                    break;
                }
            }
        }
    }

    /**
     * Detect mobile browsers.
     */
    private function detectMobileBrowsers(): void
    {
        if (! $this->browserNeedContinue) {
            return;
        }

        $browserList = [
            ['Safari Mobile', '/(iPhone|iphone|iPad|iPod).*AppleWebKit\/[.0-9]+\s\(KHTML,\slike\sGecko\)\s.*Version\/[.0-9]+\sMobile\//', '/Version\/([0-9]+\.[0-9]+)(|\.[0-9]+)\sMobile\//', '1'],
            ['Firefox Focus', 'Focus/', '/Focus\/([0-9]+\.[0-9]+)/', '1'],
            ['Firefox iOS', 'FxiOS', '/FxiOS\/([0-9]+\.[0-9]+)/', '1'],
            ['Opera Mini', 'Opera Mini|OPiOS', '/(Opera Mini|OPiOS)\/([0-9]+\.[0-9]+)/', '2'],
            ['Opera Touch', 'OPT/', '/OPT\/([0-9]+\.[0-9]+)/', '1'],
            ['DuckDuckGo', 'DuckDuckGo/', '/DuckDuckGo\/([0-9]+)/', '1'],
            ['MIUI Browser', 'MiuiBrowser/', '/MiuiBrowser\/([0-9]+\.[0-9]+)/', '1'],
            ['Mint Browser', 'Mint Browser/', '/Mint\sBrowser\/([0-9]+\.[0-9]+)/', '1'],
            ['Google App', ' GSA/', '/\sGSA\/([0-9]+)/', '1'],
            ['Facebook App', 'FBAV/|FBSV/', '/(FBAV|FBSV)\/([0-9]+)\./', '2'],
            ['Instagram App', 'Instagram', '/Instagram\s([0-9]+)\./', '1'],
            ['WhatsApp', 'WhatsApp', '/WhatsApp\/([0-9]+\.[0-9]+)/', '1'],
            ['Snapchat', 'Snapchat', '/\sSnapchat\/([0-9]+\.[0-9]+)/', '1'],
            ['TikTok', 'TikTok', '/TikTok\s([0-9]+\.[0-9]+)/', '1'],
            ['Twitter App', 'TwitterAndroid', '/TwitterAndroid\/([0-9]+\.[0-9]+)/', '1'],
            ['LinkedIn App', 'LinkedIn', '/LinkedIn\/([0-9]+\.[0-9]+)/', '1'],
            ['Pinterest App', 'Pinterest', '/Pinterest\/([0-9]+\.[0-9]+)/', '1'],
            ['Reddit App', 'Reddit', '/Reddit\/([0-9]+\.[0-9]+)/', '1'],
            ['Telegram', 'Telegram', '/Telegram\/([0-9]+\.[0-9]+)/', '1'],
            ['Signal', 'Signal', '/Signal\/([0-9]+\.[0-9]+)/', '1'],
            ['Line', 'Line/', '/Line\/([0-9]+\.[0-9]+)/', '1'],
            ['WeChat', 'MicroMessenger', '/MicroMessenger\/([0-9]+\.[0-9]+)/', '1'],
            ['KakaoTalk', 'KAKAOTALK', '/KAKAOTALK\s([0-9]+\.[0-9]+)/', '1'],
            ['Viber', 'Viber', '/Viber\/([0-9]+\.[0-9]+)/', '1'],
            ['Skype', 'Skype', '/Skype\/([0-9]+\.[0-9]+)/', '1'],
        ];

        foreach ($browserList as [$name, $match, $versionPattern, $versionIndex]) {
            if ($this->matcher->match($match)) {
                $matches = $this->matcher->match($versionPattern);

                $this->result->setBrowserName($name)
                            ->setBrowserVersion(! empty($matches[(int) $versionIndex]) ? (float) $matches[(int) $versionIndex] : 0);

                if (! empty($this->result->getBrowserVersion())) {
                    $this->browserNeedContinue = false;

                    break;
                }
            }
        }
    }

    /**
     * Detect WebView browsers.
     */
    private function detectWebView(): void
    {
        // Android WebView
        if ('Android' === $this->result->getOsName()) {
            if ($this->matcher->match('; wv|;FB|FB_IAB|OKApp')) {
                $this->result->setIsBrowserAndroidWebview(true);
            }
            if (false === $this->result->isBrowserChromeOriginal()
                && false !== $this->result->getBrowserChromiumVersion()
                && $this->matcher->match('/like\sGecko\)\sVersion\/[.0-9]+\sChrome\/[.0-9]+\s/')) {
                $this->result->setIsBrowserAndroidWebview(true);
            }
        }

        // iOS WebView
        if ($this->result->isResultIos()) {
            $webkitWebview = false;

            if (! $this->matcher->match('CriOS|FxiOS|OPiOS')
                && $this->matcher->match('/\s\((iPhone|iphone|iPad|iPod);.*\)\sAppleWebKit\/[.0-9]+\s\(KHTML\,\slike Gecko\)\s(?!Version).*Mobile\/([0-9A-Z]+)\s/')) {
                $webkitWebview = true;
            }
            if ('unknown' === $this->result->getBrowserName()
                && $this->matcher->match('MobileSafari/')
                && $this->matcher->match('CFNetwork/')) {
                $webkitWebview = true;
            }

            if ($webkitWebview) {
                $this->result->setIsBrowserIosWebview(true);

                if ('unknown' === $this->result->getBrowserName()) {
                    $this->result->setBrowserName('WebKit WebView')
                                ->setBrowserVersion(0);
                }
            }
        }
    }

    /**
     * Detect if browser is original Safari.
     */
    private function detectSafariOriginal(): void
    {
        if ('Safari' === $this->result->getBrowserName() || 'Safari Mobile' === $this->result->getBrowserName()) {
            if ($this->matcher->match('/AppleWebKit\/[.0-9]+.*Gecko\)\sVersion\/[.0-9].*Safari\/[.0-9A-Za-z]+$/')) {
                $this->result->setIsBrowserSafariOriginal(true);
            }
        }
    }

    /**
     * Finalize browser detection.
     */
    private function finalizeBrowserDetection(): void
    {
        // Check and correct browser version anomaly
        if ((int) $this->result->getBrowserVersion() > 200 && ! $this->matcher->match('FBAV/|FBSV/|GSA/|Instagram')) {
            $this->result->setBrowserVersion(0);
        }

        // Set browser title
        if (! empty($this->result->getBrowserVersion())) {
            $this->result->setBrowserTitle($this->result->getBrowserName().' '.$this->result->getBrowserVersion());
        } else {
            $browsersWithoutVersions = [
                'Android Browser', 'WebKit WebView', 'Safari SDK', 'Playstation Browser',
                'OmniWeb', 'Steam Client', 'Steam Overlay', 'Diigo Browser',
            ];

            if (in_array($this->result->getBrowserName(), $browsersWithoutVersions, true)) {
                $this->result->setBrowserTitle($this->result->getBrowserName());
            } else {
                $this->result->setBrowserTitle($this->result->getBrowserName().' (unknown version)');
            }
        }

        if (str_contains($this->result->getBrowserName(), 'unknown')) {
            $this->result->setBrowserTitle('unknown');
        }

        // EdgeHTML browser should not be detected as a Chromium engine
        if ('Edge' === $this->result->getBrowserName()
            && $this->result->getBrowserVersion() >= 12
            && $this->result->getBrowserVersion() <= 18) {
            $this->result->setBrowserChromiumVersion(0);
        }
    }
}
