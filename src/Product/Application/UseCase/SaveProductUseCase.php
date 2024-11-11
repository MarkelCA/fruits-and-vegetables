<?php

namespace Roadsurfer\Product\Application\UseCase;

use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Repository\ProductRepository;
use Roadsurfer\Product\Domain\Repository\ProductTypeRepository;

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
