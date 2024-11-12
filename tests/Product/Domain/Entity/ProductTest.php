<?php

namespace Roadsurfer\Tests\Product\Domain\Collection;

use PHPUnit\Framework\TestCase;
use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Entity\ProductType;
use Roadsurfer\Product\Domain\Enum\UnitEnum;
use Roadsurfer\Product\Domain\Exception\ProductNotValid;

class ProductTest extends TestCase
{

	public function testConstructorValidData()
	{
		$product = new Product(1, 'Apple', 100, 'g', 'fruit');

		$this->assertEquals(1, $product->getId());
		$this->assertEquals('Apple', $product->getName());
		$this->assertEquals(100, $product->getQuantity());
		$this->assertEquals('fruit', $product->getTypeName());
	}

	public function testConstructorInvalidId()
	{
		$this->expectException(ProductNotValid::class);
		$this->expectExceptionMessage("Invalid id: -1");

		new Product(-1, 'Apple', 100, 'g', 'fruit');
	}

	public function testConstructorNullId()
	{
		$product = new Product(null, 'Apple', 100, 'g', 'fruit');
		$this->assertEquals(null, $product->getId());
	}

	public function testConstructorInvalidName()
	{
		$this->expectException(ProductNotValid::class);
		$this->expectExceptionMessage("Invalid name: ");

		new Product(1, '', 100, 'g', 'invalid');
	}

	public function testConstructorInvalidQuantity()
	{
		$this->expectException(ProductNotValid::class);
		$this->expectExceptionMessage("Invalid quantity: -100");

		new Product(1, 'Apple', -100, 'g', 'fruit');
	}

	public function testTransformQuantity()
	{
		$product = new Product(1, 'Apple', 1000, 'kg', 'fruit');
		$expectedQuantity = 1000 * UnitEnum::fromString('kg')->getConversionFactor();
		$this->assertEquals($expectedQuantity, $product->getQuantity());
	}

	public function testTransformType()
	{
		$product = new Product(1, 'Apple', 100, 'g', 'fruit');
		$this->assertInstanceOf(ProductType::class, $product->getType());
		$this->assertEquals('fruit', $product->getTypeName());
	}

	public function testJsonSerialization()
	{
		$product = new Product(1, 'Apple', 100, 'g', 'fruit');
		$expectedJson = json_encode([
			'id' => 1,
			'name' => 'Apple',
			'quantity' => 100,
			'type' => 'fruit',
		]);

		$this->assertEquals($expectedJson, json_encode($product));
	}

	public function testFromArray()
	{
		$data = [
			'id' => 1,
			'name' => 'Apple',
			'quantity' => 100,
			'unit' => 'g',
			'type' => 'fruit'
		];

		$product = Product::fromArray($data);

		$this->assertEquals(1, $product->getId());
		$this->assertEquals('Apple', $product->getName());
		$this->assertEquals(100, $product->getQuantity());
		$this->assertEquals('fruit', $product->getTypeName());
	}

	public function testFromArrayNullId()
	{
		$data = [
			'id' => null,
			'name' => 'Apple',
			'quantity' => 100,
			'unit' => 'g',
			'type' => 'fruit'
		];

		$product = Product::fromArray($data);

		$this->assertEquals(null, $product->getId());
		$this->assertEquals('Apple', $product->getName());
		$this->assertEquals(100, $product->getQuantity());
		$this->assertEquals('fruit', $product->getTypeName());
	}

	public function testSetters()
	{
		$product = new Product(1, 'Apple', 100, 'g', 'fruit');

		$product->setId(2);
		$this->assertEquals(2, $product->getId());

		$product->setName('Banana');
		$this->assertEquals('Banana', $product->getName());

		$product->setQuantity(200);
		$this->assertEquals(200, $product->getQuantity());
	}
}
