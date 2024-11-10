<?php

namespace App\Product\Domain;


class ProductType
{
	private $id;
	private $name;

	public function __construct(string $name)
	{
		$this->name = $name;
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
}