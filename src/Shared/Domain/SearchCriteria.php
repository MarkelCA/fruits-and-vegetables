<?php

declare(strict_types=1);

namespace Roadsurfer\Shared\Domain;

final class SearchCriteria
{
	public function __construct(
		private array $filters,
		private array $order,
		private ?int $offset,
		private ?int $limit
	) {}

	public function filters(): array
	{
		return $this->filters;
	}

	public function order(): array
	{
		return $this->order;
	}

	public function offset(): ?int
	{
		return $this->offset;
	}

	public function limit(): ?int
	{
		return $this->limit;
	}
}
