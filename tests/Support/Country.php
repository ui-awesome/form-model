<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use UIAwesome\FormModel\BaseFormModel;

final class Country extends BaseFormModel
{
    public string $name = '';
    private string $postalCode = '';

    public function __construct(private readonly object|null $object = null) {}

    /**
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'name' => $this->name,
            'postalCode' => $this->postalCode,
            'object' => $this->object,
        ];
    }

    public function getHints(): array
    {
        return [
            'name' => 'Enter country name',
        ];
    }

    public function getLabels(): array
    {
        return [
            'name' => 'Country',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'name' => 'Enter country name',
        ];
    }
}
