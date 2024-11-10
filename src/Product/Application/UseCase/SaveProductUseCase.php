<?php

namespace App\Product\Application\UseCase;

use App\Product\Domain\Product;
use App\Product\Domain\ProductRepository;
use App\Product\Domain\ProductTypeRepository;
use App\Product\Domain\UnitRepository;

class SaveProductUseCase
{

	public function __construct(private ProductRepository $repository, private ProductTypeRepository $productTypeRepository, private UnitRepository	$unitRepository) {}

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

		if ($product->getUnitName() !== null) {
			$unit = $this->unitRepository->searchByName($product->getUnitName());
			if ($unit === null) {
				$this->unitRepository->save($product->getUnit());
			} else {
				$product->setUnit($unit);
			}
		}

		$this->repository->save($product);
	}
}
