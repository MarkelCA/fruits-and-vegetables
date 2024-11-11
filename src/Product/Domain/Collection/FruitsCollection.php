<?php

namespace Roadsurfer\Product\Domain\Collection;

use Roadsurfer\Product\Domain\Enum\ProductTypeEnum;

class FruitsCollection extends ProductCollection
{
	public static function getCollectionFilter(): ProductTypeEnum|null
	{
		return ProductTypeEnum::FRUIT;
	}
}
