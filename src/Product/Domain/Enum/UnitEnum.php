<?php

namespace Roadsurfer\Product\Domain\Enum;

use Roadsurfer\Product\Domain\Exception\UnitNotValid;

enum UnitEnum: string
{
	case GRAM = 'g';
	case KILOGRAM = 'kg';

	public static function fromString(string $unit): self
	{
		return match ($unit) {
			'g' => self::GRAM,
			'kg' => self::KILOGRAM,
			default => throw new UnitNotValid("Invalid unit: " . $unit),
		};
	}

	public function getConversionFactor(): int
	{
		return match ($this) {
			UnitEnum::GRAM => 1,
			UnitEnum::KILOGRAM => 1000,
			default => throw new UnitNotValid("Invalid unit: " . $this->value),
		};
	}
}
