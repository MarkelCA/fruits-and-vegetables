<?php

namespace App\Product\Domain;

use InvalidArgumentException;
use JsonSerializable;

class Product implements JsonSerializable
{
	private $id;
	private $name;
	private $quantity;
	private $type;
	private $unit;

	public function __construct(int $id, string $name, int $quantity, Unit $unit, ProductType $type)
	{
		$this->id = $id;
		$this->name = $name;
		$this->quantity = $quantity;
		$this->unit = $unit;
		$this->type = $type;
	}

	public static function fromArray(array $data): Product
	{ {
			$unit = match ($data['unit']) {
				'g' => UnitEnum::GRAM,
				'kg' => UnitEnum::KILOGRAM,
				default => throw new InvalidArgumentException("Invalid unit: " . $data['unit']),
			};

			$type = match ($data['type']) {
				'fruit' => ProductTypeEnum::FRUIT,
				'vegetable' => ProductTypeEnum::VEGETABLE,
				default => throw new InvalidArgumentException("Invalid type: " . $data['type']),
			};

			$productType = new ProductType($type->value);
			$productUnit = new Unit($unit->value);
			return new Product($data['id'], $data['name'], $data['quantity'], $productUnit, $productType);
		}
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

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getUnit()
	{
		return $this->unit;
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
			'unit' => $this->getUnit()->getName(),
			'type' => $this->getType()->getName(),
		];
	}

	public function __toString()
	{
		return json_encode($this);
	}
}
