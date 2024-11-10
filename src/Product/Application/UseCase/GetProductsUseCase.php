<?php

namespace App\Product\Application\UseCase;

use App\Product\Domain\ProductRepository;
use App\Shared\Domain\SearchCriteria;
use Doctrine\Common\Collections\Order;

class GetProductsUseCase
{
	public function __construct(private ProductRepository $productRepository) {}

	public function handle()
	{
		$searchCriteria = new SearchCriteria(
			filters: ['type.name' => 'fruit', 'unit.name' => 'kg'],
			order: ['id' => Order::Descending],
			offset: null,
			limit: null
		);


		return $this->productRepository->searchByCriteria($searchCriteria)->list();
	}
}
