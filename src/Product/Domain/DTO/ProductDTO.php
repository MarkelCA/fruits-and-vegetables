<?php

namespace Roadsurfer\Product\Domain\DTO;

use JsonSerializable;

class ProductDTO implements JsonSerializable
{
	private ?int $id;
	private ?string $name;
	private ?float $quantity;
	private ?string $unit;

	public function __construct(?int $id = null, ?string $name = null, ?float $quantity = null, ?string $unit = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->quantity = $quantity;
		$this->unit = $unit;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getQuantity(): ?float
	{
		return $this->quantity;
	}

	public function getUnit(): ?string
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
