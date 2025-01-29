<?php

declare(strict_types=1);

namespace App\Model\Interfaces;

use App\Components\PhotoForm\PhotoForm;

interface PhotoFormFactory
{
	function create(): PhotoForm;
}