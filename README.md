# EprofosUserAgentAnalyzerBundle

A powerful Symfony bundle for user-agent analysis. It provides accurate detection of operating systems (Windows, MacOS, Linux, iOS, Android...), browsers (Chrome, Firefox, Safari...), and device types (Desktop, Mobile, Tablet, TV...). Supports specific version detection and includes advanced handling of special cases like WebViews and compatibility modes.

[![Latest Stable Version](https://poser.pugx.org/eprofos/user-agent-analyzer/v/stable)](https://packagist.org/packages/eprofos/user-agent-analyzer)
[![License](https://poser.pugx.org/eprofos/user-agent-analyzer/license)](https://packagist.org/packages/eprofos/user-agent-analyzer)
[![Tests](https://github.com/eprofos/user-agent-analyzer/actions/workflows/tests.yml/badge.svg)](https://github.com/eprofos/user-agent-analyzer/actions/workflows/tests.yml)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.3-8892BF.svg)](https://php.net/)
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
  - Comprehensive logging support
  - High accuracy through multiple detection methods
  - Easy integration with Symfony applications
  - Extensive test coverage
  - PSR-3 logging support

## Requirements

- PHP 8.3 or higher
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
use Symfony\Component\HttpFoundation\RequestStack;

class YourController
{
    public function someAction(
        UserAgentAnalyzer $analyzer,
        RequestStack $requestStack
    ) {
        // Get user agent string from current request
        $userAgent = $requestStack->getCurrentRequest()->headers->get('User-Agent');
        
        // Analyze user agent
        $result = $analyzer->analyze($userAgent);
        
        // Access the results
        $osName = $result->getOsName();           // e.g., "Windows"
        $osVersion = $result->getOsVersion();     // e.g., 10.0
        $browserName = $result->getBrowserName(); // e.g., "Chrome"
        $deviceType = $result->getDeviceType();   // e.g., "desktop"
        
        // Get all information as array
        $allInfo = $result->toArray();
    }
}
```

### Advanced Usage with Touch Support Mode

```php
use Symfony\Component\HttpFoundation\Request;

class YourController
{
    public function someAction(
        UserAgentAnalyzer $analyzer,
        Request $request
    ) {
        // Get user agent and analyze with touch support
        $userAgent = $request->headers->get('User-Agent');
        $result = $analyzer->analyze($userAgent, true);

        // Check for specific browser features
        $isWebView = $result->getBrowserAndroidWebview() || $result->getBrowserIosWebview();
        $isDesktopMode = $result->getBrowserDesktopMode();
        $is64Bit = $result->getBits64Mode();
    }
}
```

### With Logging

```php
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class YourService
{
    public function __construct(
        private UserAgentAnalyzer $analyzer,
        private LoggerInterface $logger,
        private RequestStack $requestStack,
    ) {}

    public function analyzeCurrentUserAgent(): UserAgentResult
    {
        $userAgent = $this->requestStack->getCurrentRequest()->headers->get('User-Agent');
        return $this->analyzer->analyze($userAgent);
        // Logs will automatically be generated with PSR-3 logger
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

### Device Information
- `getDeviceType()`: Get device type
- `getBrowserAndroidWebview()`: Check if Android WebView
- `getBrowserIosWebview()`: Check if iOS WebView
- `getBrowserDesktopMode()`: Check if desktop mode
- `getBits64Mode()`: Check if 64-bit mode

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
