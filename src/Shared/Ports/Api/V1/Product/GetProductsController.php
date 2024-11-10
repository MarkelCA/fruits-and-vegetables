<?php

namespace App\Shared\Ports\Api\V1\Product;

use App\Product\Application\UseCase\GetProductsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'v1_product_get_products', methods: ['GET'])]
class GetProductsController extends AbstractController
{
	public function __construct(private GetProductsUseCase $useCase) {}

	public function __invoke(): Response
	{
		return $this->json($this->useCase->handle());
	}
}
