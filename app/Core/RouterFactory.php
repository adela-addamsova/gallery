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

		$router->addRoute('admin/deleteImage/<id>', 'Admin:Admin:deleteImage');

		$router->addRoute('admin/editPhoto/<id>', 'Admin:EditPhoto:default');


		$router->addRoute('gallery/<category>/<page>', ['module' => 'Front', 'presenter' => 'Gallery', 'action' => 'default', 'category' => 'default', 'page' => 1]);

		$router->addRoute('admin/<page \d+>', ['module' => 'Admin', 'presenter' => 'Admin', 'action' => 'default']);


		$router->addRoute('admin/<presenter>/<action>[</id>]', ['module' => 'Admin', 'presenter' => 'Admin', 'action' => 'default', 'id' => null]);
		
		$router->addRoute('<presenter>/<action>', ['module' => 'Front', 'presenter' => 'Home', 'action' => 'default']);

		return $router;
	}
}
