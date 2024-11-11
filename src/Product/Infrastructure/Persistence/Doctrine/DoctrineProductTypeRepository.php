<?php

declare(strict_types=1);

namespace Roadsurfer\Product\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Roadsurfer\Product\Domain\Entity\ProductType;
use Roadsurfer\Product\Domain\Repository\ProductTypeRepository;

final class DoctrineProductTypeRepository extends ServiceEntityRepository implements ProductTypeRepository
{

	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, ProductType::class);
	}

	public function save(ProductType $product): void
	{
		$this->add($product, true);
	}

	public function add(ProductType $product, bool $flush = false): void
	{
		$this->getEntityManager()->persist($product);
		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function search(int $id): ProductType
	{
		$product = $this->getEntityManager()->getRepository(ProductType::class)->find($id);
		return $product;
	}

	public function searchAll(): array
	{
		$results = $this->getEntityManager()->getRepository(ProductType::class)->findAll();
		$productCollection = [];
		foreach ($results as $result) {
			$productCollection[] = $result;
		}
		return $productCollection;
	}

	public function searchByName(string $id): ProductType|null
	{
		$product = $this->getEntityManager()->getRepository(ProductType::class)->findBy(['name' => $id])[0] ?? null;
		return $product;
	}
}
