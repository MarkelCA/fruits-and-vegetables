<?php

use PHPUnit\Framework\TestCase;
use Roadsurfer\Product\Application\UseCase\SaveProductUseCase;
use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Entity\ProductType;
use Roadsurfer\Product\Domain\Repository\ProductRepository;
use Roadsurfer\Product\Domain\Repository\ProductTypeRepository;
use PHPUnit\Framework\MockObject\MockObject;

class SaveProductUseCaseTest extends TestCase
{
	private SaveProductUseCase $useCase;
	private MockObject|ProductRepository $productRepositoryMock;
	private MockObject|ProductTypeRepository $productTypeRepositoryMock;

	protected function setUp(): void
	{
		/** @var ProductRepository|MockObject */
		$this->productRepositoryMock = $this->createMock(ProductRepository::class);
		/** @var ProductTypeRepository|MockObject */
		$this->productTypeRepositoryMock = $this->createMock(ProductTypeRepository::class);
		$this->useCase = new SaveProductUseCase(
			$this->productRepositoryMock,
			$this->productTypeRepositoryMock
		);
	}

	private function createProduct(int $id, string $name, string $typeName): Product
	{
		return new Product($id, $name, 100, 'g', $typeName);
	}

	public function testExecuteWhenTypeDoesNotExist(): void
	{
		$product = $this->createProduct(1, 'Apple', 'fruit');

		// Mock the productTypeRepository to return null when searching by name (type not found)
		$this->productTypeRepositoryMock
			->expects($this->once())
			->method('searchByName')
			->with('fruit')
			->willReturn(null);

		// Expect that the new product type will be saved
		$this->productTypeRepositoryMock
			->expects($this->once())
			->method('save')
			->with($product->getType());

		// Expect the product to be saved in the product repository
		$this->productRepositoryMock
			->expects($this->once())
			->method('save')
			->with($product);

		$this->useCase->execute($product);
	}

	public function testExecuteWhenTypeExists(): void
	{
		$product = $this->createProduct(1, 'Apple', 'fruit');
		$existingType = new ProductType('fruit');

		// Mock the productTypeRepository to return the existing type
		$this->productTypeRepositoryMock
			->expects($this->once())
			->method('searchByName')
			->with('fruit')
			->willReturn($existingType);

		// The product's type should be set to the existing type
		$product->setType($existingType);

		// Expect the product to be saved in the product repository
		$this->productRepositoryMock
			->expects($this->once())
			->method('save')
			->with($product);

		$this->useCase->execute($product);
	}
}
