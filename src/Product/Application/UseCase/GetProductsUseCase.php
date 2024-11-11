<?php

namespace Roadsurfer\Product\Application\UseCase;

use Roadsurfer\Product\Domain\Collection\ProductCollection;
use Roadsurfer\Product\Domain\DTO\ProductDTO;
use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Enum\UnitEnum;
use Roadsurfer\Product\Domain\Repository\ProductRepository;
use Roadsurfer\Shared\Domain\SearchCriteria;

class GetProductsUseCase
{
	public function __construct(private ProductRepository $productRepository) {}

	public function handle(?SearchCriteria $searchCriteria, UnitEnum $unit): array
	{
		if ($searchCriteria !== null) {
			$products =  $this->productRepository->searchByCriteria($searchCriteria);
			return $this->parseDTOs($products, $unit);
		}

		$products = $this->productRepository->searchAll();
		return $this->parseDTOs($products, $unit);
	}

	public function parseDTOs(ProductCollection $collection, UnitEnum $unit): array
	{
		return array_map(function (Product $product) use ($unit) {
			return new ProductDTO(
				id: $product->getId(),
				name: $product->getName(),
				quantity: $product->getQuantity() / $unit->getConversionFactor(),
				unit: $unit->value
			);
		}, $collection->list());
	}
}
