<?php

namespace App\Product\Domain;

enum ProductTypeEnum: string
{
	case FRUIT = 'fruit';
	case VEGETABLE = 'vegetable';
}
