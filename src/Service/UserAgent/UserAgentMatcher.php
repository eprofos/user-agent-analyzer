<?php

declare(strict_types=1);

namespace Eprofos\UserAgentAnalyzerBundle\Service\UserAgent;

/**
 * Provides methods for matching patterns in user agent strings.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class UserAgentMatcher
{
    private string $userAgent;

    public function __construct(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * Common User-Agent matching.
     *
     * @param string $data            The string to data search for
     * @param bool   $caseInsensitive Determines if we do a case-sensitive search (false) or a case insensitive (true)
     *
     * @return bool|array<int, int|string> returns true/matches array if $data found in $userAgent property, false otherwise
     */
    public function match(string $data, bool $caseInsensitive = false): bool|array
    {
        if (empty($data)) {
            return false;
        }

        if ('/' === substr($data, 0, 1) && '/' === substr($data, -1)) {
            if (true === $caseInsensitive) {
                $data .= 'i';
            }
            if (preg_match($data, $this->userAgent, $matches)) {
                if (! isset($matches[0])) {
                    $matches[0] = 0;
                }
                if (! isset($matches[1])) {
                    $matches[1] = 0;
                }
                if (! isset($matches[2])) {
                    $matches[2] = 0;
                }

                return $matches;
            }

            return false;
        }

        $dataArray = explode('|', $data);
        foreach ($dataArray as $value) {
            if (false === $caseInsensitive) {
                if (str_contains($this->userAgent, $value)) {
                    return true;
                }
            } else {
                if (false !== stripos($this->userAgent, $value)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Case insensitive matching.
     *
     * @return bool|array<int, int|string>
     */
    public function matchCaseInsensitive(string $data): bool|array
    {
        return $this->match($data, true);
    }

    /**
     * Get the user agent string.
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}
