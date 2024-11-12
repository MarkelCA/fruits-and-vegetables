<?php

namespace Tests\Roadsurfer\Product\Ports\Cli;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Roadsurfer\Product\Ports\Cli\SaveProductsCommand;
use Symfony\Component\Console\Command\Command;

class SaveProductsCommandTest extends KernelTestCase
{
	private Filesystem $filesystem;
	private string $tempFilePath;

	protected function setUp(): void
	{
		$this->filesystem = new Filesystem();
		$this->tempFilePath = sys_get_temp_dir() . '/test_products.json';

		$sampleData = json_encode([
			[
				"id" => 1,
				"name" => "Carrot",
				"type" => "vegetable",
				"quantity" => 10922,
				"unit" => "g"
			],
			[
				"id" => 2,
				"name" => "Apples",
				"type" => "fruit",
				"quantity" => 20,
				"unit" => "kg"
			]
		]);
		$this->filesystem->dumpFile($this->tempFilePath, $sampleData);
	}

	protected function tearDown(): void
	{
		$this->filesystem->remove($this->tempFilePath);
	}

	public function testExecuteWithValidFilePath(): void
	{
		self::bootKernel();

		$command = self::getContainer()->get(SaveProductsCommand::class);
		$commandTester = new CommandTester($command);

		$commandTester->execute([
			'filepath' => $this->tempFilePath,
		]);

		$output = $commandTester->getDisplay();
		$statusCode = $commandTester->getStatusCode();

		$this->assertEquals(Command::SUCCESS, $statusCode);
		$this->assertStringContainsString("Fruits stored: 1", $output);
		$this->assertStringContainsString("Vegetables stored: 1", $output);
	}
}
