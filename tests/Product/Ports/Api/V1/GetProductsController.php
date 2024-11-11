<?php

namespace Tests\Roadsurfer\Product\Ports\Api\V1;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Roadsurfer\Product\Application\UseCase\GetProductsUseCase;
use Roadsurfer\Shared\Domain\SearchCriteria;
use Roadsurfer\Product\Domain\Enum\UnitEnum;
use Roadsurfer\Product\Domain\Collection\ProductCollection;
use PHPUnit\Framework\MockObject\MockObject;

class GetProductsControllerTest extends WebTestCase
{
	private MockObject|GetProductsUseCase $useCaseMock;

	protected function setUp(): void
	{
		// Create a mock for GetProductsUseCase
		$this->useCaseMock = $this->createMock(GetProductsUseCase::class);
	}

	public function testGetProductsWithValidRequest(): void
	{
		// Arrange
		$client = static::createClient();
		$searchCriteria = new SearchCriteria(filters: ['type.name' => 'fruit'], order: ['id' => 'ASC']);
		$unitEnum = UnitEnum::GRAM;

		// Mock the expected response from the use case
		$this->useCaseMock
			->expects($this->once())
			->method('handle')
			->with($this->equalTo($searchCriteria), $this->equalTo($unitEnum))
			->willReturn([]);

		// Make the request to the API with valid parameters
		$client->request('GET', '/api/products', [
			'query' => [
				'type' => 'fruit',
				'orderBy' => 'id',
				'order' => 'ASC',
				'unit' => UnitEnum::GRAM->value,
			]
		]);

		// Assert the response status code and content type
		$this->assertResponseIsSuccessful();
		$this->assertResponseHeaderSame('Content-Type', 'application/json');
		$this->assertJson($client->getResponse()->getContent());
	}

	public function testGetProductsWithInvalidType(): void
	{
		// Arrange
		$client = static::createClient();

		// Make the request with an invalid 'type' parameter
		$client->request('GET', '/api/products', [
			'query' => ['type' => 123] // Invalid type (not a string)
		]);

		// Assert the response status code and error message
		$this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
		$this->assertStringContainsString('Invalid type', $client->getResponse()->getContent());
	}

	public function testGetProductsWithDomainException(): void
	{
		// Arrange
		$client = static::createClient();

		// Mock the use case to throw a DomainException
		$this->useCaseMock
			->expects($this->once())
			->method('handle')
			->willThrowException(new \Roadsurfer\Shared\Domain\DomainException("Error"));

		// Make the request
		$client->request('GET', '/api/products');

		// Assert the response status code for error
		$this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
		$this->assertStringContainsString("Error", $client->getResponse()->getContent());
	}

	public function testInvalidUnit(): void
	{
		// Arrange
		$client = static::createClient();

		// Make the request with an invalid 'unit' parameter
		$client->request('GET', '/api/products', [
			'query' => ['unit' => 'invalid_unit'] // Invalid unit
		]);

		// Assert the response status code and error message
		$this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
		$this->assertStringContainsString('Invalid unit', $client->getResponse()->getContent());
	}
}
