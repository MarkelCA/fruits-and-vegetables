<?php

namespace App\Shared\Ports\Cli;

use App\Product\Application\UseCase\SaveProductUseCase;
use App\Product\Domain\ProductCollection;
use App\Product\Domain\ProductTypeEnum;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

// the name of the command is what users type after "php bin/console"
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

			$fruits = ProductCollection::fromAssociativeArray($products, ProductTypeEnum::FRUIT);
			$vegetables = ProductCollection::fromAssociativeArray($products, ProductTypeEnum::VEGETABLE);

			$fruitsStored = 0;
			foreach ($fruits->getIterator() as $product) {
				$this->saveProductUseCase->execute($product);
				$fruitsStored++;
			}

			$vegetablesStored = 0;
			foreach ($vegetables->getIterator() as $product) {
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
