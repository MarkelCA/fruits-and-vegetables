<?php

namespace App\Product\Domain;

use InvalidArgumentException;
use JsonSerializable;

class Product implements JsonSerializable
{
	private int $id;
	private string $name;
	private int $quantity;
	private ProductType $type;

	public function __construct(int $id, string $name, int $quantity, string $unit, string $type)
	{
		$this->validateScalars(id: $id, name: $name, quantity: $quantity);
		$typeEntity = $this->transformType($type);
		$quantity = $this->transformQuantity($unit, $quantity);

		$this->id = $id;
		$this->name = $name;
		$this->quantity = $quantity;
		$this->type = $typeEntity;
	}
	public function transformQuantity(string $unit, int $quantity): int
	{
		$unitEnum = match ($unit) {
			'g' => UnitEnum::GRAM,
			'kg' => UnitEnum::KILOGRAM,
			default => throw new InvalidArgumentException("Invalid unit: " . $unit),
		};

		$unitEquivalence = match ($unitEnum) {
			UnitEnum::GRAM => 1,
			UnitEnum::KILOGRAM => 1000,
		};

		return $quantity * $unitEquivalence;
	}

	public function transformType(string $type): ProductType
	{
		$typeEnum = match ($type) {
			'fruit' => ProductTypeEnum::FRUIT,
			'vegetable' => ProductTypeEnum::VEGETABLE,
			default => throw new InvalidArgumentException("Invalid type: " . $type),
		};

		return new ProductType($typeEnum->value);
	}

	public function validateScalars(int|null $id, string $name, int $quantity): void
	{
		if ($id !== null && $id < 0) {
			throw new InvalidArgumentException("Invalid id: " . $id);
		}

		if (empty($name)) {
			throw new InvalidArgumentException("Invalid name: " . $name);
		}

		if ($quantity < 0) {
			throw new InvalidArgumentException("Invalid quantity: " . $quantity);
		}
	}

	public function transformEnums(string $unit, string $type): array
	{
		$unitEnum = match ($unit) {
			'g' => UnitEnum::GRAM,
			'kg' => UnitEnum::KILOGRAM,
			default => throw new InvalidArgumentException("Invalid unit: " . $unit),
		};

		$typeEnum = match ($type) {
			'fruit' => ProductTypeEnum::FRUIT,
			'vegetable' => ProductTypeEnum::VEGETABLE,
			default => throw new InvalidArgumentException("Invalid type: " . $type),
		};

		return ['type' => new ProductType($typeEnum->value), 'unit' => new Unit($unitEnum->value)];
	}

	public static function fromArray(array $data): Product
	{
		return new Product(id: $data['id'], name: $data['name'], quantity: $data['quantity'], unit: $data['unit'], type: $data['type']);
	}

	public function getId()
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

	public function getQuantity()
	{
		return $this->quantity;
	}

	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getTypeName()
	{
		return $this->type->getName();
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getUnit()
	{
		return $this->unit;
	}

	public function getUnitName()
	{
		return $this->unit->getName();
	}

	public function setUnit($unit)
	{
		$this->unit = $unit;
	}

	public function jsonSerialize(): mixed
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'quantity' => $this->quantity,
			'unit' => $this->getUnitName(),
			'type' => $this->getTypeName(),
		];
	}

	public function __toString()
	{
		return json_encode($this);
	}
}
