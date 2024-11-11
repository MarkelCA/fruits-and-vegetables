<?php

namespace Roadsurfer\Product\Domain\Enum;

use Roadsurfer\Product\Domain\Exception\ProductTypeNotValid;

enum ProductTypeEnum: string
{
	case FRUIT = 'fruit';
	case VEGETABLE = 'vegetable';

	public static function fromString(string $type): self
	{
		return match ($type) {
			'fruit' => self::FRUIT,
			'vegetable' => self::VEGETABLE,
			default => throw new ProductTypeNotValid("Invalid type: " . $type),
		};
	}
}
