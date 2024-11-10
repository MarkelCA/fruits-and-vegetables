<?php

declare(strict_types=1);

namespace Roadsurfer\Product\Domain;

use Roadsurfer\Product\Domain\ProductType;

interface ProductTypeRepository
{
	public function save(ProductType $product): void;

	public function searchAll(): array;

	public function search(int $id): ProductType;
	public function searchByName(string $id): ProductType|null;
}
