<?php

namespace Roadsurfer\Product\Ports\Api\V1;

use Roadsurfer\Product\Application\UseCase\SaveProductUseCase;
use Roadsurfer\Product\Domain\Entity\Product;
use Roadsurfer\Shared\Domain\DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/', name: 'v1_product_create_product', methods: ['POST'])]
class CreateProductController extends AbstractController
{
	public function __construct(private SaveProductUseCase $useCase) {}

	public function __invoke(Request $request): Response
	{
		try {
			if (!$request->getContent()) {
				throw new BadRequestException("Empty request");
			}

			$payload = json_decode($request->getContent(), true);

			if (!is_array($payload)) {
				throw new BadRequestException("Couldn't parse the request");
			}
			$product = Product::fromArray($payload);
			$this->useCase->execute($product);
			return new Response(status: Response::HTTP_CREATED);
		} catch (BadRequestException | DomainException $e) {
			return new Response("Error: " . $e->getMessage(), Response::HTTP_BAD_REQUEST);
		} catch (Throwable) {
			return new Response("Error: Internal server error", Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}
