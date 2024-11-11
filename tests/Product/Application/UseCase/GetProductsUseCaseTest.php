<?php

namespace Roadsurfer\Product\Application\UseCase;

use PHPUnit\Framework\TestCase;
use Roadsurfer\Product\Application\UseCase\GetProductsUseCase;
use Roadsurfer\Product\Domain\DTO\ProductDTO;
use Roadsurfer\Product\Domain\Collection\ProductCollection;
use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Enum\UnitEnum;
use Roadsurfer\Product\Domain\Repository\ProductRepository;
use Roadsurfer\Shared\Domain\SearchCriteria;
use PHPUnit\Framework\MockObject\MockObject;

class GetProductsUseCaseTest extends TestCase
{
	private GetProductsUseCase $useCase;
	private MockObject|ProductRepository $productRepositoryMock;

	protected function setUp(): void
	{
		/** @var ProductRepository|MockObject */
		$this->productRepositoryMock = $this->createMock(ProductRepository::class);
		$this->useCase = new GetProductsUseCase($this->productRepositoryMock);
	}

	private function createProduct(int $id, string $name, int $quantity, string $unit): Product
	{
		return new Product($id, $name, $quantity, $unit, 'fruit');
	}

	public function testHandleWithSearchCriteria(): void
	{
		/** @var SearchCriteria|MockObject */
		$searchCriteria = $this->createMock(SearchCriteria::class);

		$product1 = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value);
		$product2 = $this->createProduct(2, 'Banana', 200, UnitEnum::GRAM->value);
		$collection = new ProductCollection();
		$collection->add($product1);
		$collection->add($product2);

		$this->productRepositoryMock
			->expects($this->once())
			->method('searchByCriteria')
			->with($searchCriteria)
			->willReturn($collection);

		$unit = UnitEnum::GRAM;
		$result = $this->useCase->handle($searchCriteria, $unit);

		$this->assertCount(2, $result);
		$this->assertInstanceOf(ProductDTO::class, $result[0]);
		$this->assertEquals('Apple', $result[0]->getName());
		$this->assertEquals(100, $result[0]->getQuantity());
	}

	public function testHandleWithoutSearchCriteria(): void
	{
		$product1 = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value);
		$product2 = $this->createProduct(2, 'Banana', 200, UnitEnum::GRAM->value);
		$collection = new ProductCollection();
		$collection->add($product1);
		$collection->add($product2);

		$this->productRepositoryMock
			->expects($this->once())
			->method('searchAll')
			->willReturn($collection);

		$unit = UnitEnum::GRAM;
		$result = $this->useCase->handle(null, $unit);

		$this->assertCount(2, $result);
		$this->assertInstanceOf(ProductDTO::class, $result[0]);
		$this->assertEquals('Apple', $result[0]->getName());
		$this->assertEquals(100, $result[0]->getQuantity());
	}

	public function testParseDTOs(): void
	{
		$product1 = $this->createProduct(1, 'Apple', 100, UnitEnum::GRAM->value);
		$product2 = $this->createProduct(2, 'Banana', 200, UnitEnum::GRAM->value);
		$collection = new ProductCollection();
		$collection->add($product1);
		$collection->add($product2);

		$unit = UnitEnum::KILOGRAM;
		$dtos = $this->useCase->parseDTOs($collection, $unit);

		$this->assertCount(2, $dtos);
		$this->assertInstanceOf(ProductDTO::class, $dtos[0]);
		$this->assertEquals(0.1, $dtos[0]->getQuantity()); // 100g = 0.1kg
		$this->assertEquals(0.2, $dtos[1]->getQuantity()); // 200g = 0.2kg
	}
}
