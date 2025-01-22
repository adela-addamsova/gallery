<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList { $router = new RouteList; // Add a route for the gallery with a category parameter 
		$router->addRoute('gallery/<category>', 'Gallery:default');

		$router->addRoute('<presenter>/<action>[/<id>]', 'Home:default'); return $router;
	}
}
