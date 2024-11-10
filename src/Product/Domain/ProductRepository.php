<?php

declare(strict_types=1);

namespace Roadsurfer\Product\Domain;

use Roadsurfer\Product\Domain\Product;
use Roadsurfer\Shared\Domain\SearchCriteria;

interface ProductRepository
{
	public function save(Product $product): void;

	public function searchAll(): ProductCollection;

	public function search(int $id): Product;

	public function searchByCriteria(SearchCriteria $criteria): ProductCollection;
}
