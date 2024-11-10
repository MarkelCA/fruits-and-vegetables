<?php

namespace App\Shared\Domain;

use App\Product\Domain\Product;
use Iterator;

abstract class AbstractProductCollection
{
	abstract public function add(Product $item): void;

	abstract public function remove(int $id): void;

	abstract public function get(int $id);

	/**
	 * @return Product[]
	 */
	abstract public function list(): array;

	abstract public function getIterator(): Iterator;

	static public function fromArray(array $data): self
	{
		$collection = new static();
		foreach ($data as $item) {
			$collection->add(Product::fromArray($item));
		}
		return $collection;
	}
}
