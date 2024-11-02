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

    private int $browserChromeOriginal = 0;

    private int $browserFirefoxOriginal = 0;

    private int $browserSafariOriginal = 0;

    private int $browserChromiumVersion = 0;

    private float|int $browserGeckoVersion = 0;

    private float $browserWebkitVersion = 0;

    private int $browserAndroidWebview = 0;

    private int $browserIosWebview = 0;

    private int $browserDesktopMode = 0;

    private int $bits64Mode = 0;

    private int $macosVersionMinor = 0;

    private bool $resultIos = false;

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
     * Get the value of browserChromeOriginal.
     */
    public function getBrowserChromeOriginal(): int
    {
        return $this->browserChromeOriginal;
    }

    /**
     * Set the value of browserChromeOriginal.
     */
    public function setBrowserChromeOriginal(int $browserChromeOriginal): self
    {
        $this->browserChromeOriginal = $browserChromeOriginal;

        return $this;
    }

    /**
     * Get the value of browserFirefoxOriginal.
     */
    public function getBrowserFirefoxOriginal(): int
    {
        return $this->browserFirefoxOriginal;
    }

    /**
     * Set the value of browserFirefoxOriginal.
     */
    public function setBrowserFirefoxOriginal(int $browserFirefoxOriginal): self
    {
        $this->browserFirefoxOriginal = $browserFirefoxOriginal;

        return $this;
    }

    /**
     * Get the value of browserSafariOriginal.
     */
    public function getBrowserSafariOriginal(): int
    {
        return $this->browserSafariOriginal;
    }

    /**
     * Set the value of browserSafariOriginal.
     */
    public function setBrowserSafariOriginal(int $browserSafariOriginal): self
    {
        $this->browserSafariOriginal = $browserSafariOriginal;

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
     * Get the value of browserAndroidWebview.
     */
    public function getBrowserAndroidWebview(): int
    {
        return $this->browserAndroidWebview;
    }

    /**
     * Set the value of browserAndroidWebview.
     */
    public function setBrowserAndroidWebview(int $browserAndroidWebview): self
    {
        $this->browserAndroidWebview = $browserAndroidWebview;

        return $this;
    }

    /**
     * Get the value of browserIosWebview.
     */
    public function getBrowserIosWebview(): int
    {
        return $this->browserIosWebview;
    }

    /**
     * Set the value of browserIosWebview.
     */
    public function setBrowserIosWebview(int $browserIosWebview): self
    {
        $this->browserIosWebview = $browserIosWebview;

        return $this;
    }

    /**
     * Get the value of browserDesktopMode.
     */
    public function getBrowserDesktopMode(): int
    {
        return $this->browserDesktopMode;
    }

    /**
     * Set the value of browserDesktopMode.
     */
    public function setBrowserDesktopMode(int $browserDesktopMode): self
    {
        $this->browserDesktopMode = $browserDesktopMode;

        return $this;
    }

    /**
     * Get the value of bits64Mode.
     */
    public function getBits64Mode(): int
    {
        return $this->bits64Mode;
    }

    /**
     * Set the value of bits64Mode.
     */
    public function setBits64Mode(int $bits64Mode): self
    {
        $this->bits64Mode = $bits64Mode;

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
     * Get the value of resultIos.
     */
    public function getResultIos(): bool
    {
        return $this->resultIos;
    }

    /**
     * Set the value of resultIos.
     */
    public function setResultIos(bool $resultIos): self
    {
        $this->resultIos = $resultIos;

        return $this;
    }

    /**
     * Convert the result to an array.
     *
     * @return array<string, string|float|int>
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
            'browser_chrome_original' => $this->browserChromeOriginal,
            'browser_firefox_original' => $this->browserFirefoxOriginal,
            'browser_safari_original' => $this->browserSafariOriginal,
            'browser_chromium_version' => $this->browserChromiumVersion,
            'browser_gecko_version' => $this->browserGeckoVersion,
            'browser_webkit_version' => $this->browserWebkitVersion,
            'browser_android_webview' => $this->browserAndroidWebview,
            'browser_ios_webview' => $this->browserIosWebview,
            'browser_desktop_mode' => $this->browserDesktopMode,
            '64bits_mode' => $this->bits64Mode,
        ];
    }
}
