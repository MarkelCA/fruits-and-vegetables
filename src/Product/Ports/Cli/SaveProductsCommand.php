<?php

namespace Roadsurfer\Product\Ports\Cli;

use Roadsurfer\Product\Application\UseCase\SaveProductUseCase;
use Roadsurfer\Product\Domain\Collection\FruitsCollection;
use Roadsurfer\Product\Domain\Collection\VegetablesCollection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(name: 'products:save')]
class SaveProductsCommand extends Command
{
	const FILEPATH_ARGUMENT = 'filepath';

	public function __construct(private SaveProductUseCase $saveProductUseCase)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this->addArgument(self::FILEPATH_ARGUMENT, InputArgument::REQUIRED, 'File path to the JSON file with the products.');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		try {
			$filepath = $input->getArgument(self::FILEPATH_ARGUMENT);
			$content = file_get_contents($filepath);
			$products = json_decode($content, true);

			$fruits = FruitsCollection::fromAssociativeArray($products);
			$vegetables = VegetablesCollection::fromAssociativeArray($products);

			$fruitsStored = 0;
			foreach ($fruits->list() as $product) {
				$this->saveProductUseCase->execute($product);
				$fruitsStored++;
			}

			$vegetablesStored = 0;
			foreach ($vegetables->list() as $product) {
				$this->saveProductUseCase->execute($product);
				$vegetablesStored++;
			}

			echo "Fruits stored: $fruitsStored\n";
			echo "Vegetables stored: $vegetablesStored\n";

			return Command::SUCCESS;
		} catch (Throwable $e) {
			echo "Error: " . $e->getMessage();
			return Command::FAILURE;
		}
	}
}
