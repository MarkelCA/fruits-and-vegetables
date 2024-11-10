<?php

namespace App\Product\Domain;

class FruitsCollection extends ProductCollection
{
	public static function getCollectionFilter(): ProductTypeEnum|null
	{
		return ProductTypeEnum::FRUIT;
	}
}
