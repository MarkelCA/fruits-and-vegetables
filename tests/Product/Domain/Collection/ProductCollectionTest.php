<?php

namespace Roadsurfer\Tests\Product\Domain\Collection;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Roadsurfer\Product\Domain\Collection\FruitsCollection;
use Roadsurfer\Product\Domain\Collection\ProductCollection;
use Roadsurfer\Product\Domain\Collection\VegetablesCollection;
use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Enum\ProductTypeEnum;
use Roadsurfer\Product\Domain\Enum\UnitEnum;


class ProductCollectionTest extends TestCase
{
	private ProductCollection $collection;

	protected function setUp(): void
	{
		$this->collection = new ProductCollection();
	}

	private function createProduct(int $id, string $name, int $quantity, string $unit, string $type): Product
	{
		return new Product($id, $name, $quantity, $unit, $type);
	}

	public function testAddProduct(): void
	{
		$product = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value, ProductTypeEnum::FRUIT->value);
		$this->collection->add($product);

		$this->assertCount(1, $this->collection->list());
		$this->assertSame($product, $this->collection->get(1));
	}

	public function testRemoveProduct(): void
	{
		$product = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value, ProductTypeEnum::FRUIT->value);
		$this->collection->add($product);
		$this->collection->remove(1);

		$this->assertCount(0, $this->collection->list());
		$this->assertNull($this->collection->get(1));
	}

	public function testGetProduct(): void
	{
		$product = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value, ProductTypeEnum::FRUIT->value);
		$this->collection->add($product);

		$fetchedProduct = $this->collection->get(1);

		$this->assertNotNull($fetchedProduct);
		$this->assertEquals(1, $fetchedProduct->getId());
		$this->assertEquals('Apple', $fetchedProduct->getName());
	}

	public function testGetNonexistentProduct(): void
	{
		$fetchedProduct = $this->collection->get(99);

		$this->assertNull($fetchedProduct);
	}

	public function testListProducts(): void
	{
		$product1 = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value, ProductTypeEnum::FRUIT->value);
		$product2 = $this->createProduct(2, 'Carrot', 200, UnitEnum::GRAM->value, ProductTypeEnum::VEGETABLE->value);

		$this->collection->add($product1);
		$this->collection->add($product2);

		$list = $this->collection->list();

		$this->assertCount(2, $list);
		$this->assertSame([$product1, $product2], $list);
	}

	public function testGetIterator(): void
	{
		$product1 = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value, ProductTypeEnum::FRUIT->value);
		$product2 = $this->createProduct(2, 'Carrot', 200, UnitEnum::GRAM->value, ProductTypeEnum::VEGETABLE->value);

		$this->collection->add($product1);
		$this->collection->add($product2);

		$iterator = $this->collection->getIterator();

		$this->assertInstanceOf(ArrayIterator::class, $iterator);
		$this->assertCount(2, iterator_to_array($iterator));
	}

	public function testGetCollectionFilter(): void
	{
		$this->assertNull(ProductCollection::getCollectionFilter());
	}

	public function testFromAssociativeArray(): void
	{
		$data = [
			['id' => 1, 'name' => 'Apple', 'quantity' => 100, 'unit' => 'g', 'type' => 'fruit'],
			['id' => 2, 'name' => 'Carrot', 'quantity' => 200, 'unit' => 'g', 'type' => 'vegetable'],
		];

		$collection = ProductCollection::fromAssociativeArray($data);

		$this->assertCount(2, $collection->list());
		$this->assertEquals('Apple', $collection->get(1)->getName());
		$this->assertEquals('Carrot', $collection->get(2)->getName());
	}

	public function testFromProductListWithFilter(): void
	{
		$product1 = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value, ProductTypeEnum::FRUIT->value);
		$product2 = $this->createProduct(2, 'Carrot', 200, UnitEnum::GRAM->value, ProductTypeEnum::VEGETABLE->value);

		$collection = ProductCollection::fromProductList([$product1, $product2]);
		$fruitCollection = FruitsCollection::fromProductList([$product1, $product2]);
		$vegetableCollection = VegetablesCollection::fromProductList([$product1, $product2]);

		$this->assertCount(2, $collection->list());
		$this->assertEquals('Apple', $collection->get(1)->getName());

		$this->assertCount(1, $fruitCollection->list());
		$this->assertEquals('Apple', $fruitCollection->get(1)->getName());

		$this->assertCount(1, $vegetableCollection->list());
		$this->assertEquals('Carrot', $vegetableCollection->get(2)->getName());
	}

	public function testFromProductListWithoutFilter(): void
	{
		$product1 = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value, ProductTypeEnum::FRUIT->value);
		$product2 = $this->createProduct(2, 'Carrot', 200, UnitEnum::GRAM->value, ProductTypeEnum::VEGETABLE->value);

		$collection = ProductCollection::fromProductList([$product1, $product2]);
		$fruitCollection = FruitsCollection::fromProductList([$product1, $product2]);
		$vegetableCollection = VegetablesCollection::fromProductList([$product1, $product2]);

		$this->assertCount(2, $collection->list());
		$this->assertEquals('Apple', $collection->get(1)->getName());
		$this->assertEquals('Carrot', $collection->get(2)->getName());

		$this->assertCount(1, $fruitCollection->list());
		$this->assertEquals('Apple', $fruitCollection->get(1)->getName());

		$this->assertCount(1, $vegetableCollection->list());
		$this->assertEquals('Carrot', $vegetableCollection->get(2)->getName());
	}

	public function testPrint(): void
	{
		$product1 = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value, ProductTypeEnum::FRUIT->value);
		$product2 = $this->createProduct(2, 'Carrot', 200, UnitEnum::GRAM->value, ProductTypeEnum::VEGETABLE->value);

		$this->collection->add($product1);
		$this->collection->add($product2);

		$this->expectOutputString($product1->__toString() . "\n" . $product2->__toString() . "\n");
		$this->collection->print();
	}

	public function testPrintEmptyCollection(): void
	{
		$this->expectOutputString('');
		$this->collection->print();
	}
}
