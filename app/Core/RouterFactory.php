<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

		$router->addRoute('about', 'Front:Home:about');
		$router->addRoute('gallery/<category>/<page=1>', 'Front:Gallery:default');
		$router->addRoute('admin/<page \d+>', 'Admin:default');
		$router->addRoute('<presenter>/<action>', 'Front:Home:default');

		return $router;
	}
}
