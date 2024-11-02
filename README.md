# EprofosUserAgentAnalyzerBundle

A powerful Symfony bundle for user-agent analysis. It provides accurate detection of operating systems (Windows, MacOS, Linux, iOS, Android...), browsers (Chrome, Firefox, Safari...), and device types (Desktop, Mobile, Tablet, TV...). Supports specific version detection and includes advanced handling of special cases like WebViews and compatibility modes.

[![Latest Stable Version](https://poser.pugx.org/eprofos/user-agent-analyzer/v/stable)](https://packagist.org/packages/eprofos/user-agent-analyzer)
[![License](https://poser.pugx.org/eprofos/user-agent-analyzer/license)](https://packagist.org/packages/eprofos/user-agent-analyzer)
[![Tests](https://github.com/eprofos/user-agent-analyzer/actions/workflows/tests.yml/badge.svg)](https://github.com/eprofos/user-agent-analyzer/actions/workflows/tests.yml)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-8892BF.svg)](https://php.net/)
[![Symfony Version](https://img.shields.io/badge/symfony-%5E7.0-000000.svg)](https://symfony.com/)

## Features

- **Operating System Detection**
  - Windows, MacOS, Linux, iOS, Android, and more
  - Version detection with codename support for MacOS
  - Mobile OS variants (MIUI, EMUI, ColorOS, etc.)
  - 64-bit mode detection

- **Browser Detection**
  - Major browsers: Chrome, Firefox, Safari, Edge, Opera
  - Mobile browsers and WebViews
  - Version detection and rendering engine identification
  - Chromium/Gecko/WebKit version tracking
  - Desktop mode detection

- **Device Detection**
  - Device type classification (Desktop, Mobile, Tablet, TV, etc.)
  - Smart device detection (Smart TV, Game Consoles, Car Systems)
  - Touch support detection
  - WebView detection for Android and iOS

- **Advanced Features**
  - Automatic User-Agent detection from current request
  - Comprehensive logging support
  - High accuracy through multiple detection methods
  - Easy integration with Symfony applications
  - Extensive test coverage
  - PSR-3 logging support

## Requirements

- PHP 8.2 or higher
- Symfony 7.0 or higher

## Installation

### Using Composer

```bash
composer require eprofos/user-agent-analyzer
```

### Enable the Bundle

If you're not using Symfony Flex, add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    Eprofos\UserAgentAnalyzerBundle\EprofosUserAgentAnalyzerBundle::class => ['all' => true],
];
```

## Usage

### Basic Usage

```php
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer;

class YourController
{
    public function someAction(UserAgentAnalyzer $analyzer)
    {
        $result = $analyzer->analyzeCurrentRequest();
        
        // Access the results
        $osName = $result->getOsName();           // e.g., "Windows"
        $osVersion = $result->getOsVersion();     // e.g., 10.0
        $browserName = $result->getBrowserName(); // e.g., "Chrome"
        $deviceType = $result->getDeviceType();   // e.g., "desktop"
        
        // Check device type
        $isMobile = $result->isMobile();    // true if device is mobile
        $isDesktop = $result->isDesktop();  // true if device is desktop
        $isTablet = $result->isTablet();    // true if device is tablet
        
        // Get all information as array
        $allInfo = $result->toArray();
    }
}
```

### Advanced Usage with Touch Support Mode

```php
use Eprofos\UserAgentAnalyzerBundle\Service\UserAgentAnalyzer;

class YourController
{
    public function someAction(
        UserAgentAnalyzer $analyzer,
        Request $request
    ) {
    {
        // Analyze current request with touch support detection
        $result = $analyzer->analyzeCurrentRequest(true);

        // Or analyze specific user agent with touch support
        $userAgent = $request->headers->get('User-Agent');
        $result = $analyzer->analyze($userAgent, true);

        // Check for specific browser features
        $isWebView = $result->isBrowserAndroidWebview() || $result->isBrowserIosWebview();
        $isDesktopMode = $result->isBrowserDesktopMode();
        $is64Bit = $result->is64BitsMode();
    }
}
```

## Available Methods

### Operating System Information
- `getOsType()`: Get OS type (desktop, mobile, etc.)
- `getOsFamily()`: Get OS family (windows, macintosh, etc.)
- `getOsName()`: Get OS name
- `getOsVersion()`: Get OS version
- `getOsTitle()`: Get formatted OS name with version

### Browser Information
- `getBrowserName()`: Get browser name
- `getBrowserVersion()`: Get browser version
- `getBrowserTitle()`: Get formatted browser name with version
- `getBrowserChromiumVersion()`: Get Chromium version if applicable
- `getBrowserGeckoVersion()`: Get Gecko version if applicable
- `getBrowserWebkitVersion()`: Get WebKit version if applicable
- `isBrowserChromeOriginal()`: Check if browser is original Chrome
- `isBrowserFirefoxOriginal()`: Check if browser is original Firefox
- `isBrowserSafariOriginal()`: Check if browser is original Safari

### Device Information
- `getDeviceType()`: Get device type
- `isMobile()`: Check if device is mobile
- `isDesktop()`: Check if device is desktop
- `isTablet()`: Check if device is tablet
- `isBrowserAndroidWebview()`: Check if Android WebView
- `isBrowserIosWebview()`: Check if iOS WebView
- `isBrowserDesktopMode()`: Check if desktop mode
- `is64BitsMode()`: Check if 64-bit mode

## Testing

```bash
composer test
```

## Quality Tools

```bash
# Run PHP CS Fixer
composer cs-fix

# Run PHPStan
composer phpstan

# Run all quality tools
composer analyze
```

## Contributing

Feel free to contribute to this bundle by submitting issues and pull requests.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This bundle is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.

## Credits

Developed by [Eprofos](https://www.eprofos.com).
