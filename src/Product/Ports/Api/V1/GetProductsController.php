<?php

namespace Roadsurfer\Product\Ports\Api\V1;

use Roadsurfer\Product\Application\UseCase\GetProductsUseCase;
use Roadsurfer\Shared\Domain\SearchCriteria;
use Doctrine\Common\Collections\Order;
use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Product\Domain\Enum\UnitEnum;
use Roadsurfer\Shared\Domain\DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/', name: 'v1_product_get_products', methods: ['GET'])]
class GetProductsController extends AbstractController
{
	public function __construct(private GetProductsUseCase $useCase) {}

	public function __invoke(Request $request): Response
	{
		try {

			$type = $request->query->get('type');
			$orderBy = $request->query->get('orderBy') ?? 'id';
			$order = strtoupper($request->query->get('order') ?? Order::Ascending->value);
			$unit = $request->query->get('unit') ?? UnitEnum::GRAM->value;

			$this->validateRequest($orderBy, $order, $unit, $order);

			$searchCriteria = new SearchCriteria(
				filters: !empty($type) ? ['type.name' => $type] : [],
				order: [$orderBy => $order],
				offset: null,
				limit: null
			);

			$unitEnum = UnitEnum::fromString($unit);
			return $this->json($this->useCase->handle($searchCriteria, $unitEnum));
		} catch (DomainException | BadRequestException $e) {
			return new Response("Error: " . $e->getMessage(), Response::HTTP_BAD_REQUEST);
		} catch (Throwable) {
			return new Response("Error: Internal server error", Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * @throws BadRequestException
	 */
	public function validateRequest($orderBy = null, $orderType = null, $unit = null, $order = null): void
	{

		if ($orderBy !== null && !is_string($orderBy)) {
			throw new BadRequestException("Invalid orderBy: " . $orderBy);
		} elseif (!in_array($orderBy, Product::getOrderedFields())) {
			throw new BadRequestException("Invalid orderBy: " . $orderBy);
		}

		if ($orderType !== null && !is_string($orderType)) {
			throw new BadRequestException("Invalid orderType: " . $orderType);
		}

		if ($unit !== null && !is_string($unit)) {
			throw new BadRequestException("Invalid unit: " . $unit);
		}

		if ($order !== null && !in_array($order, [Order::Ascending->value, Order::Descending->value])) {
			throw new BadRequestException("Invalid order: " . $order);
		}
	}
}
