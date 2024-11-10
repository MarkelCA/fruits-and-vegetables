<?php

namespace App\Product\Ports\Api\V1\Product;

use App\Product\Application\UseCase\SaveProductUseCase;
use App\Product\Domain\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'v1_product_create_product', methods: ['POST'])]
class CreateProductController extends AbstractController
{

	public function __construct(private SaveProductUseCase $useCase) {}

	public function __invoke(Request $request): Response
	{
		$payload = json_decode($request->getContent(), true);

		$product = Product::fromArray($payload);
		$this->useCase->execute($product);

		return new Response(status: Response::HTTP_CREATED);
	}
}
