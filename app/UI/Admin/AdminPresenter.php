<?php

declare(strict_types=1);

namespace App\UI\Admin;

use App\Components\CategoriesTable\CategoriesTable as CategoriesTableCategoriesTable;
use App\Components\ImagesTable\CategoriesTable;
use App\Components\ImagesTable\ImagesTable;
use App\Model\Facades\ImageFacade;
use Nette;
use Nette\Application\UI\Presenter;

class AdminPresenter extends Presenter {
    /** @var \App\Components\ImagesTable\ImagesTable @inject */
    public $imagesTable;

    /** @var \App\Components\CategoriesTable\CategoriesTable @inject */
    public $categoriesTable;

    protected function createComponentImagesTable(): ImagesTable
    {
        return $this->imagesTable;
    }
    protected function createComponentCategoriesTable(): CategoriesTableCategoriesTable
    {
        return $this->categoriesTable;
    }
}