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

		// Front
		$router->addRoute('about', 'Front:Home:about');
		$router->addRoute('gallery/<category>[/<page=1>]', 'Front:Gallery:default');
		$router->addRoute('<presenter>/<action>[/<id>]', 'Front:Home:default');

		// Admin
		$router->addRoute('admin/<presenter>/<action>[/<id=null>]', 'Admin:Admin:default');

		return $router;
	}
}
