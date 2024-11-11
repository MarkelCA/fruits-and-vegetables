<?php

namespace Roadsurfer\Product\Domain\DTO;

use JsonSerializable;

class ProductDTO implements JsonSerializable
{
	private int|null $id;
	private string|null $name;
	private float|null $quantity;
	private string|null $unit;

	public function __construct(int|null $id = null, string|null $name = null, float|null $quantity = null, string|null $unit = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->quantity = $quantity;
		$this->unit = $unit;
	}

	public function getId(): int|null
	{
		return $this->id;
	}

	public function getName(): string|null
	{
		return $this->name;
	}

	public function getQuantity(): float|null
	{
		return $this->quantity;
	}

	public function getUnit(): string|null
	{
		return $this->unit;
	}

	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'quantity' => $this->quantity,
			'unit' => $this->unit
		];
	}
}
