<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class NumericInput extends Field
{
    protected string $view = 'forms.components.numeric-input';

    protected int|null $minValue = null;
    protected int|null $maxValue = null;

    public function minValue(int $value): static
    {
        $this->minValue = $value;
        return $this;
    }

    public function maxValue(int $value): static
    {
        $this->maxValue = $value;
        return $this;
    }

    public function getMinValue(): int|null
    {
        return $this->minValue;
    }

    public function getMaxValue(): int|null
    {
        return $this->maxValue;
    }
}
