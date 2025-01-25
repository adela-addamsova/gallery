<?php

declare(strict_types=1);

namespace App\UI\Admin;

use App\Components\CategoriesTable\CategoriesTable as CategoriesTableCategoriesTable;
use App\Components\ImagesTable\CategoriesTable;
use App\Components\ImagesTable\ImagesTable;
use App\Components\Paginator\MyPaginator;
use App\Components\TablePaginator\TablePaginator;
use App\Model\Facades\AdminFacade;
use App\Model\Facades\ImageFacade;
use Nette;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;

class AdminPresenter extends Presenter {
    /** @var \App\Components\ImagesTable\ImagesTable @inject */
    public $imagesTable;

    /** @var \App\Components\CategoriesTable\CategoriesTable @inject */
    public $categoriesTable;

    /** @var AdminFacade @inject */
    public $adminFacade;


    // protected function createComponentImagesTable(): ImagesTable
    // {
    //     return new ImagesTable($this->adminFacade);
    // }
    protected function createComponentCategoriesTable(): CategoriesTableCategoriesTable
    {
        return $this->categoriesTable;
    }

 
    public function renderDefault(int $page = 1): void
    {
        $columns = [
            'description' => 'Description',
            'path' => 'Image path',
            'thumb_path' => 'Thumb path',
            'category_id' => 'Category ID',
            'uploaded_at' => 'Uploaded at'
        ];

        $imageCount = $this->adminFacade->getTotalImagesCount();

   
        $paginator = new Paginator;
        $paginator->setItemCount($imageCount); // total items count
        $paginator->setItemsPerPage(10); // items per page
        $paginator->setPage($page); // actual page number

       
        $images = $this->adminFacade->showImages($paginator->getLength(), $paginator->getOffset());

    
        $this->template->images = $images;
        $this->template->paginator = $paginator;
        $this->template->columns = $columns;
  

    
    }

}

