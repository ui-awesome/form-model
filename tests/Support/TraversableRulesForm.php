<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use Generator;
use UIAwesome\FormModel\BaseFormModel;

/**
 * Stub form model exposing validation rules through a generator used for tests.
 */
final class TraversableRulesForm extends BaseFormModel
{
    public string $name = '';

    /**
     * @return Generator<string, array<int, string>>
     */
    public function getRules(): iterable
    {
        yield 'name' => ['required'];
    }
}
