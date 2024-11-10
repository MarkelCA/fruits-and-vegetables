<?php

namespace App\Product\Domain;

class VegetablesCollection extends ProductCollection
{
	public static function getCollectionFilter(): ProductTypeEnum|null
	{
		return ProductTypeEnum::VEGETABLE;
	}
}
