<?php


namespace Tests\Roadsurfer\Product\Ports\Api\V1;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateProductControllerTest extends WebTestCase
{
	public function testCreateProductSuccess()
	{
		$client = static::createClient();
		$client->request(
			method: 'POST',
			uri: '/v1/products/',
			parameters: [],
			files: [],
			server: ['CONTENT_TYPE' => 'application/json'],
			content: json_encode([
				'id' => 1,
				'name' => 'Watermelon',
				'quantity' => 1.5,
				'unit' => 'kg',
				'type' => 'vegetable'
			])
		);

		$this->assertEquals(201, $client->getResponse()->getStatusCode());
	}

	public function testCreateProductEmptyPayload()
	{
		$client = static::createClient();
		$client->request('POST', '/v1/products/', [], [], ['CONTENT_TYPE' => 'application/json'], '');

		$this->assertEquals(400, $client->getResponse()->getStatusCode());
		$this->assertStringContainsString('Error: Empty request', $client->getResponse()->getContent());
	}

	public function testCreateProductInvalidJson()
	{
		$client = static::createClient();
		$client->request(
			'POST',
			'/v1/products/',
			[],
			[],
			['CONTENT_TYPE' => 'application/json'],
			'invalid json'
		);

		$this->assertEquals(400, $client->getResponse()->getStatusCode());
		$this->assertStringContainsString("Error: Couldn't parse the request", $client->getResponse()->getContent());
	}

	public function testCreateProductInvalidData()
	{
		$client = static::createClient();
		$client->request(
			'POST',
			'/v1/products/',
			[],
			[],
			['CONTENT_TYPE' => 'application/json'],
			json_encode([
				'id' => -1, // Invalid ID
				'name' => '',
				'quantity' => -10,
				'unit' => 'kg',
				'type' => 'vegetable'
			])
		);

		$this->assertEquals(400, $client->getResponse()->getStatusCode());
		$this->assertStringContainsString('Error: Invalid', $client->getResponse()->getContent());
	}

	public function testCreateProductInternalError()
	{
		$client = static::createClient();

		// Simulate internal server error by causing an unexpected error
		$client->request(
			'POST',
			'/v1/products/',
			[],
			[],
			['CONTENT_TYPE' => 'application/json'],
			json_encode([
				'id' => 1,
				'name' => 'ValidProduct',
				'quantity' => 10,
				'unit' => 'kg',
				'type' => 'nonexistentType' // This should trigger an exception in transformType
			])
		);

		$this->assertEquals(400, $client->getResponse()->getStatusCode());
		$this->assertStringContainsString('Error: Invalid type', $client->getResponse()->getContent());
	}
}
