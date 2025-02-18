<?php

declare(strict_types=1);

namespace App\Components\CategoriesTable;

use App\Model\Facades\AdminFacade;
use Nette\Application\UI\Control;

class CategoriesTable extends Control
{

    // AdminFacade instance for interacting with the database to fetch categories
    private AdminFacade $adminFacade;

    /**
     * Constructor for the CategoriesTable component
     * 
     * @param AdminFacade $adminFacade - Used to interact with the database to fetch categories
     */
    public function __construct(AdminFacade $adminFacade)
    {
        $this->adminFacade = $adminFacade;
    }

    /**
     * Renders the Categories Table component
     * 
     * Fetches categories from the AdminFacade and passes them to the template for rendering
     */
    public function render(): void
    {
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
            'background_path' => 'Background path',
            'icon_path' => 'Icon path',
            'created_at' => 'Created at'
        ];

        $categories = $this->adminFacade->showCategories();

        $this->template->categories = $categories;
        $this->template->columns = $columns;

        $this->template->setFile(__DIR__ . '/CategoriesTable.latte');
        $this->template->render();
    }
}
