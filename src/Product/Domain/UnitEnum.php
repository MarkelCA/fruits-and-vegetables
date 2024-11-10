<?php

namespace Roadsurfer\Product\Domain;

use InvalidArgumentException;

enum UnitEnum: string
{
	case GRAM = 'g';
	case KILOGRAM = 'kg';

	public static function fromString(string $unit): self
	{
		return match ($unit) {
			'g' => self::GRAM,
			'kg' => self::KILOGRAM,
			default => throw new InvalidArgumentException("Invalid unit: " . $unit),
		};
	}

	public function getGramsEquivalence(): int
	{
		return match ($this) {
			UnitEnum::GRAM => 1,
			UnitEnum::KILOGRAM => 1000,
		};
	}
}
