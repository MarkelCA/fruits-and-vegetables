<?php

namespace Roadsurfer\Product\Application\UseCase;

use Roadsurfer\Product\Domain\ProductRepository;
use Roadsurfer\Shared\Domain\SearchCriteria;

class GetProductsUseCase
{
	public function __construct(private ProductRepository $productRepository) {}

	public function handle(SearchCriteria|null $searchCriteria)
	{
		if ($searchCriteria !== null) {
			return $this->productRepository->searchByCriteria($searchCriteria)->list();
		}

		return $this->productRepository->searchAll()->list();
	}
}
