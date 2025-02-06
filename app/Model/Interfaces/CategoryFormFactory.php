<?php

declare(strict_types=1);

namespace App\Model\Interfaces;

use App\Components\CategoryForm\CategoryForm;

interface CategoryFormFactory
{
	function create(): CategoryForm;
}