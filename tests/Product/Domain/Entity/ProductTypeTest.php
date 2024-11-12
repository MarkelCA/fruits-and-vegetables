<?php

namespace Roadsurfer\Tests\Product\Domain\Collection;

use PHPUnit\Framework\TestCase;
use Roadsurfer\Product\Domain\Entity\ProductType;
use Roadsurfer\Product\Domain\Exception\ProductTypeNotValid;

class ProductTypeTest extends TestCase
{

	public function testConstructorValidData()
	{
		$productType = new ProductType('fruit');

		$this->assertEquals('fruit', $productType->getName());
	}

	public function testConstructorInvalidName()
	{
		$this->expectException(ProductTypeNotValid::class);
		$this->expectExceptionMessage("Invalid name: ");

		new ProductType('');
	}
}
