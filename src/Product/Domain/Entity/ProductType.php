<?php

namespace Roadsurfer\Product\Domain\Entity;

use Roadsurfer\Product\Domain\Exception\ProductTypeNotValid;

class ProductType
{
	private ?int $id;
	private string $name;

	public function __construct($name)
	{
		$this->validate($name);
		$this->name = $name;
	}

	public function validate($name): void
	{
		if (empty($name) || !is_string($name)) {
			throw new ProductTypeNotValid("Invalid name: " . $name);
		}
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
}
