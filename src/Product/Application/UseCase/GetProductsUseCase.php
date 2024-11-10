<?php

namespace App\Product\Application\UseCase;

use App\Product\Domain\ProductRepository;
use App\Shared\Domain\SearchCriteria;
use Doctrine\Common\Collections\Order;

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
