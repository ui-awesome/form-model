<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use UIAwesome\Html\Interop\InputInterface;

final class InputWidget implements InputInterface
{
    public function __toString(): string
    {
        return $this->render();
    }

    public function attributes(array $attributes): static
    {
        return $this;
    }

    public function class(string $value, bool $override = false): static
    {
        return $this;
    }

    public function fieldAttributes(string $formModel, string $property): static
    {
        return $this;
    }

    public function id(string|null $value): static
    {
        return $this;
    }

    public function render(): string
    {
        return '';
    }
}
