<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Tests\Service\UserAgent;

use Eprofos\UserAgentAnalyzerBundle\Service\UserAgent\UserAgentMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests for UserAgentMatcher.
 */
#[CoversClass(UserAgentMatcher::class)]
class UserAgentMatcherTest extends TestCase
{
    private const TEST_UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    private UserAgentMatcher $matcher;

    protected function setUp(): void
    {
        $this->matcher = new UserAgentMatcher(self::TEST_UA);
    }

    public function testMatch(): void
    {
        // Test simple string matching
        $this->assertTrue($this->matcher->match('Chrome'));
        $this->assertTrue($this->matcher->match('Windows'));
        $this->assertFalse($this->matcher->match('Firefox'));

        // Test OR pattern matching
        $this->assertTrue($this->matcher->match('Firefox|Chrome|Safari'));
        $this->assertFalse($this->matcher->match('Firefox|Opera|Edge'));

        // Test regex pattern matching
        $matches = $this->matcher->match('/Chrome\/([0-9]+\.[0-9]+)/');
        $this->assertIsArray($matches);
        $this->assertEquals('120.0', $matches[1]);

        // Test empty pattern
        $this->assertFalse($this->matcher->match(''));
    }

    public function testMatchCaseInsensitive(): void
    {
        $this->assertTrue($this->matcher->matchCaseInsensitive('CHROME'));
        $this->assertTrue($this->matcher->matchCaseInsensitive('chrome'));
        $this->assertTrue($this->matcher->matchCaseInsensitive('Chrome'));
        $this->assertFalse($this->matcher->matchCaseInsensitive('firefox'));
    }

    public function testGetUserAgent(): void
    {
        $this->assertEquals(self::TEST_UA, $this->matcher->getUserAgent());
    }

    #[DataProvider('regexPatternDataProvider')]
    public function testRegexPatternMatching(string $pattern, array $expectedMatches): void
    {
        $matches = $this->matcher->match($pattern);
        $this->assertIsArray($matches);
        foreach ($expectedMatches as $index => $expected) {
            $this->assertEquals($expected, $matches[$index]);
        }
    }

    public static function regexPatternDataProvider(): array
    {
        return [
            [
                '/Chrome\/([0-9]+)\.([0-9]+)/',
                ['Chrome/120.0', '120', '0'],
            ],
            [
                '/Windows NT ([0-9]+\.[0-9]+)/',
                ['Windows NT 10.0', '10.0'],
            ],
            [
                '/(Mozilla)\/([0-9]+\.[0-9]+)/',
                ['Mozilla/5.0', 'Mozilla', '5.0'],
            ],
        ];
    }
}
