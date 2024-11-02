<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Model;

/**
 * Contains all the properties and results from user agent parsing.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class UserAgentResult
{
    private string $osType = 'unknown';

    private string $osFamily = 'unknown';

    private string $osName = 'unknown';

    private float|int $osVersion = 0;

    private string $osTitle = 'unknown';

    private string $deviceType = 'unknown';

    private string $browserName = 'unknown';

    private float|int $browserVersion = 0;

    private string $browserTitle = 'unknown';

    private bool $isBrowserChromeOriginal = false;

    private bool $isBrowserFirefoxOriginal = false;

    private bool $isBrowserSafariOriginal = false;

    private int $browserChromiumVersion = 0;

    private float|int $browserGeckoVersion = 0;

    private float $browserWebkitVersion = 0;

    private bool $isBrowserAndroidWebview = false;

    private bool $isBrowserIosWebview = false;

    private bool $isBrowserDesktopMode = false;

    private bool $is64BitsMode = false;

    private int $macosVersionMinor = 0;

    private bool $isResultIos = false;

    /**
     * Get the value of osType.
     */
    public function getOsType(): string
    {
        return $this->osType;
    }

    /**
     * Set the value of osType.
     */
    public function setOsType(string $osType): self
    {
        $this->osType = $osType;

        return $this;
    }

    /**
     * Get the value of osFamily.
     */
    public function getOsFamily(): string
    {
        return $this->osFamily;
    }

    /**
     * Set the value of osFamily.
     */
    public function setOsFamily(string $osFamily): self
    {
        $this->osFamily = $osFamily;

        return $this;
    }

    /**
     * Get the value of osName.
     */
    public function getOsName(): string
    {
        return $this->osName;
    }

    /**
     * Set the value of osName.
     */
    public function setOsName(string $osName): self
    {
        $this->osName = $osName;

        return $this;
    }

    /**
     * Get the value of osVersion.
     */
    public function getOsVersion(): float|int
    {
        return $this->osVersion;
    }

    /**
     * Set the value of osVersion.
     */
    public function setOsVersion(float|int $osVersion): self
    {
        $this->osVersion = $osVersion;

        return $this;
    }

    /**
     * Get the value of osTitle.
     */
    public function getOsTitle(): string
    {
        return $this->osTitle;
    }

    /**
     * Set the value of osTitle.
     */
    public function setOsTitle(string $osTitle): self
    {
        $this->osTitle = $osTitle;

        return $this;
    }

    /**
     * Get the value of deviceType.
     */
    public function getDeviceType(): string
    {
        return $this->deviceType;
    }

    /**
     * Set the value of deviceType.
     */
    public function setDeviceType(string $deviceType): self
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * Get the value of browserName.
     */
    public function getBrowserName(): string
    {
        return $this->browserName;
    }

    /**
     * Set the value of browserName.
     */
    public function setBrowserName(string $browserName): self
    {
        $this->browserName = $browserName;

        return $this;
    }

    /**
     * Get the value of browserVersion.
     */
    public function getBrowserVersion(): float|int
    {
        return $this->browserVersion;
    }

    /**
     * Set the value of browserVersion.
     */
    public function setBrowserVersion(float|int $browserVersion): self
    {
        $this->browserVersion = $browserVersion;

        return $this;
    }

    /**
     * Get the value of browserTitle.
     */
    public function getBrowserTitle(): string
    {
        return $this->browserTitle;
    }

    /**
     * Set the value of browserTitle.
     */
    public function setBrowserTitle(string $browserTitle): self
    {
        $this->browserTitle = $browserTitle;

        return $this;
    }

    /**
     * Check if browser is original Chrome.
     */
    public function isBrowserChromeOriginal(): bool
    {
        return $this->isBrowserChromeOriginal;
    }

    /**
     * Set if browser is original Chrome.
     */
    public function setIsBrowserChromeOriginal(bool $isBrowserChromeOriginal): self
    {
        $this->isBrowserChromeOriginal = $isBrowserChromeOriginal;

        return $this;
    }

    /**
     * Check if browser is original Firefox.
     */
    public function isBrowserFirefoxOriginal(): bool
    {
        return $this->isBrowserFirefoxOriginal;
    }

    /**
     * Set if browser is original Firefox.
     */
    public function setIsBrowserFirefoxOriginal(bool $isBrowserFirefoxOriginal): self
    {
        $this->isBrowserFirefoxOriginal = $isBrowserFirefoxOriginal;

        return $this;
    }

    /**
     * Check if browser is original Safari.
     */
    public function isBrowserSafariOriginal(): bool
    {
        return $this->isBrowserSafariOriginal;
    }

    /**
     * Set if browser is original Safari.
     */
    public function setIsBrowserSafariOriginal(bool $isBrowserSafariOriginal): self
    {
        $this->isBrowserSafariOriginal = $isBrowserSafariOriginal;

        return $this;
    }

    /**
     * Get the value of browserChromiumVersion.
     */
    public function getBrowserChromiumVersion(): int
    {
        return $this->browserChromiumVersion;
    }

    /**
     * Set the value of browserChromiumVersion.
     */
    public function setBrowserChromiumVersion(int $browserChromiumVersion): self
    {
        $this->browserChromiumVersion = $browserChromiumVersion;

        return $this;
    }

    /**
     * Get the value of browserGeckoVersion.
     */
    public function getBrowserGeckoVersion(): float|int
    {
        return $this->browserGeckoVersion;
    }

    /**
     * Set the value of browserGeckoVersion.
     */
    public function setBrowserGeckoVersion(float|int $browserGeckoVersion): self
    {
        $this->browserGeckoVersion = $browserGeckoVersion;

        return $this;
    }

    /**
     * Get the value of browserWebkitVersion.
     */
    public function getBrowserWebkitVersion(): float
    {
        return $this->browserWebkitVersion;
    }

    /**
     * Set the value of browserWebkitVersion.
     */
    public function setBrowserWebkitVersion(float $browserWebkitVersion): self
    {
        $this->browserWebkitVersion = $browserWebkitVersion;

        return $this;
    }

    /**
     * Check if browser is Android WebView.
     */
    public function isBrowserAndroidWebview(): bool
    {
        return $this->isBrowserAndroidWebview;
    }

    /**
     * Set if browser is Android WebView.
     */
    public function setIsBrowserAndroidWebview(bool $isBrowserAndroidWebview): self
    {
        $this->isBrowserAndroidWebview = $isBrowserAndroidWebview;

        return $this;
    }

    /**
     * Check if browser is iOS WebView.
     */
    public function isBrowserIosWebview(): bool
    {
        return $this->isBrowserIosWebview;
    }

    /**
     * Set if browser is iOS WebView.
     */
    public function setIsBrowserIosWebview(bool $isBrowserIosWebview): self
    {
        $this->isBrowserIosWebview = $isBrowserIosWebview;

        return $this;
    }

    /**
     * Check if browser is in desktop mode.
     */
    public function isBrowserDesktopMode(): bool
    {
        return $this->isBrowserDesktopMode;
    }

    /**
     * Set if browser is in desktop mode.
     */
    public function setIsBrowserDesktopMode(bool $isBrowserDesktopMode): self
    {
        $this->isBrowserDesktopMode = $isBrowserDesktopMode;

        return $this;
    }

    /**
     * Check if system is in 64-bits mode.
     */
    public function is64BitsMode(): bool
    {
        return $this->is64BitsMode;
    }

    /**
     * Set if system is in 64-bits mode.
     */
    public function setIs64BitsMode(bool $is64BitsMode): self
    {
        $this->is64BitsMode = $is64BitsMode;

        return $this;
    }

    /**
     * Get the value of macosVersionMinor.
     */
    public function getMacosVersionMinor(): int
    {
        return $this->macosVersionMinor;
    }

    /**
     * Set the value of macosVersionMinor.
     */
    public function setMacosVersionMinor(int $macosVersionMinor): self
    {
        $this->macosVersionMinor = $macosVersionMinor;

        return $this;
    }

    /**
     * Check if result is iOS.
     */
    public function isResultIos(): bool
    {
        return $this->isResultIos;
    }

    /**
     * Set if result is iOS.
     */
    public function setIsResultIos(bool $isResultIos): self
    {
        $this->isResultIos = $isResultIos;

        return $this;
    }

    /**
     * Convert the result to an array.
     *
     * @return array<string, string|float|int|bool>
     */
    public function toArray(): array
    {
        return [
            'os_type' => $this->osType,
            'os_family' => $this->osFamily,
            'os_name' => $this->osName,
            'os_version' => $this->osVersion,
            'os_title' => $this->osTitle,
            'device_type' => $this->deviceType,
            'browser_name' => $this->browserName,
            'browser_version' => $this->browserVersion,
            'browser_title' => $this->browserTitle,
            'is_browser_chrome_original' => $this->isBrowserChromeOriginal,
            'is_browser_firefox_original' => $this->isBrowserFirefoxOriginal,
            'is_browser_safari_original' => $this->isBrowserSafariOriginal,
            'browser_chromium_version' => $this->browserChromiumVersion,
            'browser_gecko_version' => $this->browserGeckoVersion,
            'browser_webkit_version' => $this->browserWebkitVersion,
            'is_browser_android_webview' => $this->isBrowserAndroidWebview,
            'is_browser_ios_webview' => $this->isBrowserIosWebview,
            'is_browser_desktop_mode' => $this->isBrowserDesktopMode,
            'is_64bits_mode' => $this->is64BitsMode,
        ];
    }
}
