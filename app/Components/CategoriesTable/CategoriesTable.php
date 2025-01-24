<?php

declare(strict_types=1);

namespace App\Components\CategoriesTable;

use App\Model\Facades\AdminFacade;
use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class CategoriesTable extends Control {
  
    private AdminFacade $adminFacade;
    public function __construct(AdminFacade $adminFacade)
    {
        $this->adminFacade = $adminFacade;
    }

    public function render(): void
    {
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
            'background_path' => 'Background path',
            'created_at' => 'Created at'
        ];

        $categories = $this->adminFacade->showCategories();
        $this->template->categories = $categories;
        $this->template->columns = $columns;
        $this->template->setFile(__DIR__ . '/CategoriesTable.latte');
        $this->template->render();
    }
}