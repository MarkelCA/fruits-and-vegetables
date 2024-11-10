<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Product\Domain\Product;
use App\Shared\Domain\SearchCriteria;

interface ProductRepository
{
	public function save(Product $product): void;

	public function searchAll(): ProductCollection;

	public function search(int $id): Product;

	public function searchByCriteria(SearchCriteria $criteria): ProductCollection;
}
