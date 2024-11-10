<?php

namespace App\Product\Ports\Api\V1\Product;

use App\Product\Application\UseCase\GetProductsUseCase;
use App\Shared\Domain\SearchCriteria;
use Doctrine\Common\Collections\Order;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'v1_product_get_products', methods: ['GET'])]
class GetProductsController extends AbstractController
{
	public function __construct(private GetProductsUseCase $useCase) {}

	public function __invoke(Request $request): Response
	{
		$this->validateRequest($request);

		$type = $request->query->get('type');
		$orderBy = $request->query->get('orderBy') ?? 'id';
		$orderType = strtoupper($request->query->get('order') ?? Order::Ascending->value);

		$searchCriteria = new SearchCriteria(
			filters: !empty($type) ? ['type.name' => $type] : [],
			order: [$orderBy => $orderType],
			offset: null,
			limit: null
		);

		return $this->json($this->useCase->handle($searchCriteria));
	}

	public function validateRequest(Request $request): void
	{
		$type = $request->query->get('type');
		$orderBy = $request->query->get('orderBy');
		$orderType = $request->query->get('orderType');

		if ($type !== null && !is_string($type)) {
			throw new InvalidArgumentException("Invalid type: " . $type);
		}

		if ($orderBy !== null && !is_string($orderBy)) {
			throw new InvalidArgumentException("Invalid orderBy: " . $orderBy);
		}

		if ($orderType !== null && !is_string($orderType)) {
			throw new InvalidArgumentException("Invalid orderType: " . $orderType);
		}
	}
}