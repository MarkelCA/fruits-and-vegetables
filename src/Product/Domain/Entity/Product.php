<?php

namespace Roadsurfer\Product\Domain\Entity;

use JsonSerializable;
use Roadsurfer\Product\Domain\Enum\ProductTypeEnum;
use Roadsurfer\Product\Domain\Enum\UnitEnum;
use Roadsurfer\Product\Domain\Exception\ProductNotValid;

class Product implements JsonSerializable
{
	private ?int $id;
	private string $name;
	/**
	 * Product quantity (in grams)
	 */
	private float $quantity;
	private ProductType $type;

	public function __construct($id, $name, $quantity, $unit, $type)
	{
		$this->validateScalars(id: $id, name: $name, quantity: $quantity);
		$typeEntity = $this->transformType($type);
		$quantity = $this->transformQuantity($unit, $quantity);

		$this->id = $id;
		$this->name = $name;
		$this->quantity = $quantity;
		$this->type = $typeEntity;
	}

	public function transformQuantity(string $unit, float $quantity): float
	{
		$equivalence = UnitEnum::fromString($unit)->getConversionFactor();
		return $quantity * $equivalence;
	}

	public function transformType(string $type): ProductType
	{
		$typeEnum = ProductTypeEnum::fromString($type);
		return new ProductType($typeEnum->value);
	}

	public function validateScalars(?int $id, string $name, float $quantity): void
	{
		if ($id !== null && $id < 0) {
			throw new ProductNotValid("Invalid id: " . $id);
		}

		if (empty($name)) {
			throw new ProductNotValid("Invalid name: " . $name);
		}

		if ($quantity < 0) {
			throw new ProductNotValid("Invalid quantity: " . $quantity);
		}
	}

	public static function fromArray(array $data): Product
	{
		return new Product(id: $data['id'], name: $data['name'], quantity: $data['quantity'], unit: $data['unit'], type: $data['type']);
	}


	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getQuantity(): float
	{
		return $this->quantity;
	}

	public function setQuantity(float $quantity)
	{
		$this->quantity = $quantity;
	}

	public function getType(): ProductType
	{
		return $this->type;
	}

	public function getTypeName(): string
	{
		return $this->type->getName();
	}

	public function setType(ProductType $type)
	{
		$this->type = $type;
	}

	public function jsonSerialize(): mixed
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'quantity' => $this->quantity,
			'type' => $this->getTypeName(),
		];
	}

	public static function getOrderedFields(): array
	{
		return [
			'id',
			'name',
			'quantity',
			'type'
		];
	}

	public function __toString()
	{
		return json_encode($this);
	}
}
