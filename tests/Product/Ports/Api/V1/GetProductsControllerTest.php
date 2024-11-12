<?php

namespace Tests\Roadsurfer\Product\Ports\Api\V1;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Roadsurfer\Product\Domain\Enum\UnitEnum;

class GetProductsControllerTest extends WebTestCase
{

	public function testGetProductsWithValidRequest(): void
	{
		$client = static::createClient();
		$client->request('GET', "/v1/products/?type=fruit&orderBy=id&order=ASC&unit=" . UnitEnum::GRAM->value);

		$this->assertResponseIsSuccessful();
		$this->assertResponseHeaderSame('Content-Type', 'application/json');
		$this->assertJson($client->getResponse()->getContent());
	}

	public function testGetProductsByNonExistingType(): void
	{
		$client = static::createClient();

		$client->request('GET', '/v1/products/?type=123');

		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
		$this->assertEquals($client->getResponse()->getContent(), '[]');
	}

	public function testGetProductsByNonExistingOrderBy(): void
	{
		$client = static::createClient();
		$client->request('GET', '/v1/products/?orderBy=123');

		$this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
		// $this->assertStringContainsString('Invalid order by', $client->getResponse()->getContent());
	}

	public function testGetProductsByNonExistingOrderType(): void
	{
		$client = static::createClient();
		$client->request('GET', '/v1/products/?order=123');

		$this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
	}

	public function testGetProductsByNonExistingUnit(): void
	{
		$client = static::createClient();
		$client->request('GET', '/v1/products/?unit=123');

		$this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
	}

	public function testGetAllProducts(): void
	{
		$client = static::createClient();
		$client->request('GET', '/v1/products/');

		$this->assertResponseIsSuccessful();
		$this->assertResponseHeaderSame('Content-Type', 'application/json');
		$this->assertJson($client->getResponse()->getContent());
	}

	public function testInvalidUnit(): void
	{
		$client = static::createClient();
		$client->request('GET', '/v1/products/?unit=invalid_unit');

		$this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
		$this->assertStringContainsString('Invalid unit', $client->getResponse()->getContent());
	}
}
