<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use UIAwesome\FormModel\Attribute\{FieldConfig, Hint, Label, Placeholder};
use UIAwesome\FormModel\BaseFormModel;

/**
 * Stub form model with attribute-priority metadata used for tests.
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class AttributePriorityForm extends BaseFormModel
{
    public string $fallback = '';

    #[FieldConfig(['class' => ['attribute-priority']])]
    #[Hint('Attribute hint')]
    #[Label('Attribute label')]
    #[Placeholder('Attribute placeholder')]
    public string $name = '';

    public function getFieldConfigs(): array
    {
        return [
            'name' => [
                'class' => ['map-priority'],
            ],
            'fallback' => [
                'class' => ['map-only'],
            ],
        ];
    }

    public function getHints(): array
    {
        return [
            'name' => 'Map hint',
            'fallback' => 'Map only hint',
        ];
    }

    public function getLabels(): array
    {
        return [
            'name' => 'Map label',
            'fallback' => 'Map only label',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'name' => 'Map placeholder',
            'fallback' => 'Map only placeholder',
        ];
    }
}
