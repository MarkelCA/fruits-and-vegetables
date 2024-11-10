<?php

namespace App\Shared\Domain;

use App\Product\Domain\Product;
use App\Product\Domain\ProductTypeEnum;
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

	abstract static public function getCollectionFilter(): ProductTypeEnum|null;

	static public function fromAssociativeArray(array $data): self
	{
		$collection = new static();
		foreach ($data as $item) {
			if (static::getCollectionFilter() === null || $item['type'] === static::getCollectionFilter()->value) {
				$collection->add(Product::fromArray($item));
			}
		}
		return $collection;
	}

	static public function fromProductList(iterable $data, ProductTypeEnum|null $type = null): self
	{
		$collection = new static();
		foreach ($data as $item) {
			if ($type === null || $item->getTypeName() === static::getCollectionFilter()->value) {
				$collection->add($item);
			}
		}
		return $collection;
	}

	public function print(): void
	{
		foreach ($this->list() as $item) {
			echo "$item\n";
		}
	}
}
