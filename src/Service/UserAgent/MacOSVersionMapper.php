<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Service\UserAgent;

/**
 * Maps MacOS version numbers to their codenames.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class MacOSVersionMapper
{
    /**
     * @var array<int, string>
     */
    private const VERSION_MAP = [
        0 => 'Cheetah',
        1 => 'Puma',
        2 => 'Jaguar',
        3 => 'Panther',
        4 => 'Tiger',
        5 => 'Leopard',
        6 => 'Snow Leopard',
        7 => 'Lion',
        8 => 'Mountain Lion',
        9 => 'Mavericks',
        10 => 'Yosemite',
        11 => 'El Capitan',
        12 => 'Sierra',
        13 => 'High Sierra',
        14 => 'Mojave',
        15 => 'Catalina',
        16 => 'Big Sur',
        17 => 'Monterey',
        18 => 'Ventura',
    ];

    /**
     * Convert MacOS numeric version to MacOS codename.
     */
    public function getCodename(int $version): string
    {
        return self::VERSION_MAP[$version] ?? 'New';
    }

    /**
     * Get Darwin to MacOS version mapping.
     *
     * @return array<string, string>
     */
    public function getDarwinMacOSMap(): array
    {
        return [
            '1.3' => '0', '1.4' => '1', '5.1' => '1', '5.5' => '1',
            '6.0' => '2', '6.8' => '2', '7.0' => '3', '7.9' => '3',
            '8.0' => '4', '8.1' => '4', '9.0' => '5', '9.8' => '5',
            '10.0' => '6', '10.8' => '6', '11.0' => '7', '11.4' => '7',
            '12.0' => '8', '12.6' => '8', '13.0' => '9', '13.4' => '9',
            '14.0' => '10', '14.5' => '10', '15.0' => '11', '15.6' => '11',
            '16.0' => '12', '16.6' => '12', '17.0' => '13', '17.7' => '13',
            '18.0' => '14', '18.2' => '14', '19.0' => '15', '19.6' => '15',
            '20.0' => '16', '20.6' => '16', '21.0' => '17', '21.6' => '17',
            '22.0' => '18', '22.3' => '18',
        ];
    }

    /**
     * Get Darwin to iOS version mapping.
     *
     * @return array<string, string>
     */
    public function getDarwinIOSMap(): array
    {
        return [
            '9.0' => '1', '9.8' => '1', '10.0' => '4', '10.8' => '4',
            '11.0' => '5', '11.4' => '5', '12.0' => '6', '13.0' => '7',
            '14.0' => '8', '15.0' => '9', '15.6' => '9', '16.0' => '10',
            '16.6' => '10', '17.0' => '11', '17.7' => '11', '18.0' => '12',
            '18.2' => '12', '19.0' => '13', '19.6' => '13', '20.0' => '14',
            '20.6' => '14', '21.0' => '15', '21.6' => '15', '22.0' => '16',
            '22.3' => '16',
        ];
    }
}
