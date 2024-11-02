<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Tests\Model;

use Eprofos\UserAgentAnalyzerBundle\Model\UserAgentResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests for UserAgentResult model.
 */
#[CoversClass(UserAgentResult::class)]
class UserAgentResultTest extends TestCase
{
    private UserAgentResult $result;

    protected function setUp(): void
    {
        $this->result = new UserAgentResult();
    }

    public function testDefaultValues(): void
    {
        $this->assertEquals('unknown', $this->result->getOsType());
        $this->assertEquals('unknown', $this->result->getOsFamily());
        $this->assertEquals('unknown', $this->result->getOsName());
        $this->assertEquals(0, $this->result->getOsVersion());
        $this->assertEquals('unknown', $this->result->getOsTitle());
        $this->assertEquals('unknown', $this->result->getDeviceType());
        $this->assertEquals('unknown', $this->result->getBrowserName());
        $this->assertEquals(0, $this->result->getBrowserVersion());
        $this->assertEquals('unknown', $this->result->getBrowserTitle());
    }

    public function testSettersAndGetters(): void
    {
        $this->result->setOsType('desktop')
            ->setOsFamily('windows')
            ->setOsName('Windows')
            ->setOsVersion(10.0)
            ->setOsTitle('Windows 10')
            ->setDeviceType('desktop')
            ->setBrowserName('Chrome')
            ->setBrowserVersion(120.0)
            ->setBrowserTitle('Chrome 120.0');

        $this->assertEquals('desktop', $this->result->getOsType());
        $this->assertEquals('windows', $this->result->getOsFamily());
        $this->assertEquals('Windows', $this->result->getOsName());
        $this->assertEquals(10.0, $this->result->getOsVersion());
        $this->assertEquals('Windows 10', $this->result->getOsTitle());
        $this->assertEquals('desktop', $this->result->getDeviceType());
        $this->assertEquals('Chrome', $this->result->getBrowserName());
        $this->assertEquals(120.0, $this->result->getBrowserVersion());
        $this->assertEquals('Chrome 120.0', $this->result->getBrowserTitle());
    }

    public function testToArray(): void
    {
        $this->result->setOsType('desktop')
            ->setOsFamily('windows')
            ->setOsName('Windows')
            ->setOsVersion(10.0)
            ->setDeviceType('desktop')
            ->setBrowserName('Chrome')
            ->setBrowserVersion(120.0);

        $array = $this->result->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('desktop', $array['os_type']);
        $this->assertEquals('windows', $array['os_family']);
        $this->assertEquals('Windows', $array['os_name']);
        $this->assertEquals(10.0, $array['os_version']);
        $this->assertEquals('desktop', $array['device_type']);
        $this->assertEquals('Chrome', $array['browser_name']);
        $this->assertEquals(120.0, $array['browser_version']);
    }
}
