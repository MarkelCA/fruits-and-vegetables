<?php

namespace App\Product\Application\UseCase;

use App\Product\Domain\Product;
use App\Product\Domain\ProductRepository;
use App\Product\Domain\ProductTypeRepository;

class SaveProductUseCase
{

	public function __construct(private ProductRepository $repository, private ProductTypeRepository $productTypeRepository) {}

	public function execute(Product $product): void
	{
		if ($product->getTypeName() !== null) {
			$type = $this->productTypeRepository->searchByName($product->getTypeName());
			if ($type === null) {
				$this->productTypeRepository->save($product->getType());
			} else {
				$product->setType($type);
			}
		}

		$this->repository->save($product);
	}
}
