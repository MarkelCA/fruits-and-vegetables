<?php

namespace Roadsurfer\Tests\Product\Domain\Enum;

use PHPUnit\Framework\TestCase;
use Roadsurfer\Product\Domain\Enum\ProductTypeEnum;
use Roadsurfer\Product\Domain\Exception\ProductTypeNotValid;

class ProductTypeEnumTest extends TestCase
{
	public function testFromStringWithValidFruitType()
	{
		$result = ProductTypeEnum::fromString('fruit');
		$this->assertSame(ProductTypeEnum::FRUIT, $result);
	}

	public function testFromStringWithValidVegetableType()
	{
		$result = ProductTypeEnum::fromString('vegetable');
		$this->assertSame(ProductTypeEnum::VEGETABLE, $result);
	}

	public function testFromStringWithInvalidType()
	{
		$this->expectException(ProductTypeNotValid::class);
		$this->expectExceptionMessage('Invalid type: meat');

		ProductTypeEnum::fromString('meat');
	}
}
