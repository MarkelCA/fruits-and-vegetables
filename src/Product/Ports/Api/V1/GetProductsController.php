<?php

namespace Roadsurfer\Product\Ports\Api\V1;

use Roadsurfer\Product\Application\UseCase\GetProductsUseCase;
use Roadsurfer\Shared\Domain\SearchCriteria;
use Doctrine\Common\Collections\Order;
use Roadsurfer\Product\Domain\Enum\UnitEnum;
use Roadsurfer\Shared\Domain\DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'v1_product_get_products', methods: ['GET'])]
class GetProductsController extends AbstractController
{
	public function __construct(private GetProductsUseCase $useCase) {}

	public function __invoke(Request $request): Response
	{
		try {
			$this->validateRequest($request);

			$type = $request->query->get('type');
			$orderBy = $request->query->get('orderBy') ?? 'id';
			$orderType = strtoupper($request->query->get('order') ?? Order::Ascending->value);
			$unit = $request->query->get('unit') ?? UnitEnum::GRAM->value;

			$searchCriteria = new SearchCriteria(
				filters: !empty($type) ? ['type.name' => $type] : [],
				order: [$orderBy => $orderType],
				offset: null,
				limit: null
			);

			$unitEnum = UnitEnum::fromString($unit);
			return $this->json($this->useCase->handle($searchCriteria, $unitEnum));
		} catch (DomainException | BadRequestException $e) {
			return new Response("Error: " . $e->getMessage(), Response::HTTP_BAD_REQUEST);
		}
	}

	/**
	 * @throws BadRequestException
	 */
	public function validateRequest(Request $request): void
	{
		$type = $request->query->get('type');
		$orderBy = $request->query->get('orderBy');
		$orderType = $request->query->get('order');
		$unit = $request->query->get('unit');

		if ($type !== null && !is_string($type)) {
			throw new BadRequestException("Invalid type: " . $type);
		}

		if ($orderBy !== null && !is_string($orderBy)) {
			throw new BadRequestException("Invalid orderBy: " . $orderBy);
		}

		if ($orderType !== null && !is_string($orderType)) {
			throw new BadRequestException("Invalid orderType: " . $orderType);
		}

		if ($unit !== null && !is_string($unit)) {
			throw new BadRequestException("Invalid unit: " . $unit);
		}
	}
}
