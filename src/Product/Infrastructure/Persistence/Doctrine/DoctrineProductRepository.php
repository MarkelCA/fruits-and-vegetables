<?php

declare(strict_types=1);

namespace Roadsurfer\Product\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Roadsurfer\Product\Domain\Collection\ProductCollection;
use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Repository\ProductRepository;
use Roadsurfer\Shared\Domain\SearchCriteria;

final class DoctrineProductRepository extends ServiceEntityRepository implements ProductRepository
{

	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Product::class);
	}

	public function save(Product $product): void
	{
		$this->add($product, true);
	}

	public function add(Product $product, bool $flush = false): void
	{
		$this->getEntityManager()->persist($product);
		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function search(int $id): Product
	{
		return $this->getEntityManager()->getRepository(Product::class)->find($id);
	}

	public function searchAll(): ProductCollection
	{
		$results = $this->getEntityManager()->getRepository(Product::class)->findAll();
		return ProductCollection::fromProductList($results);
	}


	public function searchByCriteria(SearchCriteria $criteria): ProductCollection
	{
		$doctrineCriteria = Criteria::create();
		foreach ($criteria->filters() as $field => $value) {
			$doctrineCriteria->andWhere(Criteria::expr()->eq($field, $value));
		}
		$doctrineCriteria->orderBy($criteria->order());


		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('p')
			->from(Product::class, 'p')
			->join('p.type', 'type')->addCriteria($doctrineCriteria);

		$result = $qb->getQuery()->getResult();
		return ProductCollection::fromProductList($result);
	}
}
