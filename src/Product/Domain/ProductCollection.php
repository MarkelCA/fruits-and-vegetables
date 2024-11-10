<?php

namespace App\Product\Domain;

use App\Product\Domain\Product;
use App\Shared\Domain\AbstractProductCollection;
use ArrayIterator;
use Iterator;

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

	/**
	 * @return Iterator<Product>
	 */
	public function getIterator(): Iterator
	{
		return new ArrayIterator($this->items);
	}
}
