<?php

namespace Roadsurfer\Product\Domain\Collection;

use Roadsurfer\Product\Domain\Enum\ProductTypeEnum;

class VegetablesCollection extends ProductCollection
{
	public static function getCollectionFilter(): ?ProductTypeEnum
	{
		return ProductTypeEnum::VEGETABLE;
	}
}
