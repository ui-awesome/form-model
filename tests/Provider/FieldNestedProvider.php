<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Provider;

/**
 * Data provider for {@see \UIAwesome\FormModel\Tests\FieldNestedTest}.
 *
 * Provides representative input/output pairs for nested property paths and expected values.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FieldNestedProvider
{
    /**
     * @return array<string, array{string, array<string, array<int, string>>, string}>
     */
    public static function fieldConfigPathProvider(): array
    {
        return [
            'deep_city' => [
                'profile.address.city',
                [
                    'class()' => ['text-red-100 dark:text-red-100'],
                ],
                'Should return field configuration for the deeply nested city field.',
            ],
            'deep_street' => [
                'profile.address.street',
                [
                    'class()' => ['text-blue-100 dark:text-blue-100'],
                ],
                'Should return field configuration for the deeply nested street field.',
            ],
            'nested_profile' => [
                'profile.bio',
                [
                    'class()' => ['text-green-100 dark:text-green-100'],
                ],
                'Should return field configuration for the nested profile field.',
            ],
            'root' => [
                'name',
                [
                    'class()' => ['text-gray-100 dark:text-gray-100'],
                ],
                'Should return field configuration for the root field.',
            ],
        ];
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function hintPathProvider(): array
    {
        return [
            'deep_city' => [
                'profile.address.city',
                'Enter city name',
                'Should return the deeply nested city hint value.',
            ],
            'deep_country' => [
                'profile.address.country.name',
                'Enter country name',
                'Should return the deeply nested country hint value.',
            ],
            'deep_street' => [
                'profile.address.street',
                'Enter street name',
                'Should return the deeply nested street hint value.',
            ],
            'nested_profile' => [
                'profile.bio',
                'Enter your bio',
                'Should return the nested profile hint value.',
            ],
            'root' => [
                'name',
                'Enter your name',
                'Should return the root hint value.',
            ],
        ];
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function labelPathProvider(): array
    {
        return [
            'deep_city' => [
                'profile.address.city',
                'City',
                'Should return the deeply nested city label value.',
            ],
            'deep_country' => [
                'profile.address.country.name',
                'Country',
                'Should return the deeply nested country label value.',
            ],
            'deep_street' => [
                'profile.address.street',
                'Street',
                'Should return the deeply nested street label value.',
            ],
            'nested_profile' => [
                'profile.bio',
                'Bio',
                'Should return the nested profile label value.',
            ],
            'root' => [
                'name',
                'Name',
                'Should return the root label value.',
            ],
        ];
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function placeholderPathProvider(): array
    {
        return [
            'deep_city' => [
                'profile.address.city',
                'Enter city name',
                'Should return the deeply nested city placeholder value.',
            ],
            'deep_country' => [
                'profile.address.country.name',
                'Enter country name',
                'Should return the deeply nested country placeholder value.',
            ],
            'deep_street' => [
                'profile.address.street',
                'Enter street name',
                'Should return the deeply nested street placeholder value.',
            ],
            'nested_profile' => [
                'profile.bio',
                'Enter your bio',
                'Should return the nested profile placeholder value.',
            ],
            'root' => [
                'name',
                'Enter your name',
                'Should return the root placeholder value.',
            ],
        ];
    }

    /**
     * @return array<string, array{string, bool, string}>
     */
    public static function rulePathProvider(): array
    {
        return [
            'deep_street' => [
                'profile.address.street',
                false,
                'Should return validators for the deeply nested street field.',
            ],
            'nested_city_without_rules' => [
                'profile.address.city',
                true,
                'Should return null when no validators are configured for the nested field.',
            ],
            'nested_profile' => [
                'profile.bio',
                false,
                'Should return validators for the nested profile field.',
            ],
            'root' => [
                'name',
                false,
                'Should return validators for the root field.',
            ],
        ];
    }
}
