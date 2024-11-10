<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence\Doctrine;

use App\Product\Domain\Unit;
use App\Product\Domain\UnitRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineUnitRepository extends ServiceEntityRepository implements UnitRepository
{

	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Unit::class);
	}

	public function save(Unit $product): void
	{
		$this->add($product, true);
	}

	public function add(Unit $product, bool $flush = false): void
	{
		$this->getEntityManager()->persist($product);
		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function search(int $id): Unit
	{
		$product = $this->getEntityManager()->getRepository(Unit::class)->find($id);
		return $product;
	}

	public function searchAll(): array
	{
		$results = $this->getEntityManager()->getRepository(Unit::class)->findAll();
		$productCollection = [];
		foreach ($results as $result) {
			$productCollection[] = $result;
		}
		return $productCollection;
	}

	public function searchByName(string $id): Unit|null
	{
		$product = $this->getEntityManager()->getRepository(Unit::class)->findBy(['name' => $id])[0] ?? null;
		return $product;
	}
}
