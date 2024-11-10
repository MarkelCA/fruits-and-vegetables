<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Product\Domain\Unit;

interface UnitRepository
{
	public function save(Unit $product): void;

	public function searchAll(): array;

	public function search(int $id): Unit;
	public function searchByName(string $id): Unit|null;
}
