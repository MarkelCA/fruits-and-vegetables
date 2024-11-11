<?php

namespace Roadsurfer\Product\Domain\Enum;

use InvalidArgumentException;

enum ProductTypeEnum: string
{
	case FRUIT = 'fruit';
	case VEGETABLE = 'vegetable';

	public static function fromString(string $type): self
	{
		return match ($type) {
			'fruit' => self::FRUIT,
			'vegetable' => self::VEGETABLE,
			default => throw new InvalidArgumentException("Invalid type: " . $type),
		};
	}
}
