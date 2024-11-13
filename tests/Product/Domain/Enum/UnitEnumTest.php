<?php

namespace Roadsurfer\Tests\Product\Domain\Enum;

use PHPUnit\Framework\TestCase;
use Roadsurfer\Product\Domain\Enum\UnitEnum;
use Roadsurfer\Product\Domain\Exception\UnitNotValid;

class UnitEnumTest extends TestCase
{
	public function testFromStringWithValidGramUnit()
	{
		$result = UnitEnum::fromString('g');
		$this->assertSame(UnitEnum::GRAM, $result);
	}

	public function testFromStringWithValidKilogramUnit()
	{
		$result = UnitEnum::fromString('kg');
		$this->assertSame(UnitEnum::KILOGRAM, $result);
	}

	public function testFromStringWithInvalidUnit()
	{
		$this->expectException(UnitNotValid::class);
		$this->expectExceptionMessage('Invalid unit: pound');

		UnitEnum::fromString('pound');
	}

	public function testGetConversionFactorForGram()
	{
		$unit = UnitEnum::GRAM;
		$result = $unit->getConversionFactor();
		$this->assertSame(1, $result);
	}

	public function testGetConversionFactorForKilogram()
	{
		$unit = UnitEnum::KILOGRAM;
		$result = $unit->getConversionFactor();
		$this->assertSame(1000, $result);
	}
}
