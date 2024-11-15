<?php

namespace Roadsurfer\Product\Domain\Collection;

use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Collection\AbstractProductCollection;
use ArrayIterator;
use Iterator;
use Roadsurfer\Product\Domain\Enum\ProductTypeEnum;

class ProductCollection extends AbstractProductCollection
{
	private array $items = [];

	public function add(Product $item): void
	{
		$this->items[] = $item;
	}

	public function remove($key): void
	{
		foreach ($this->items as $index => $item) {
			if ($item->getId() === $key) {
				unset($this->items[$index]);
			}
		}
	}

	public function get($key)
	{
		foreach ($this->items as $item) {
			if ($item->getId() === $key) {
				return $item;
			}
		}
		return null;
	}

	public function list(): array
	{
		return $this->items;
	}

	public static function getCollectionFilter(): ?ProductTypeEnum
	{
		return null;
	}

	/**
	 * @return Iterator<Product>
	 */
	public function getIterator(): Iterator
	{
		return new ArrayIterator($this->items);
	}
}
