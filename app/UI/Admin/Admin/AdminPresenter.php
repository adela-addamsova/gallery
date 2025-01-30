<?php

declare(strict_types=1);

namespace App\UI\Admin\Admin;

use App\Components\CategoriesTable\CategoriesTable as CategoriesTableCategoriesTable;
// use App\Components\ImagesTable\ImagesTable;
use App\Components\Paginator\MyPaginator;
use App\Model\Facades\AdminFacade;
use App\UI\Admin\BasePresenter;

class AdminPresenter extends BasePresenter {

    /** @var \App\Components\ImagesTable\ImagesTable @inject */
    public $imagesTable;

    /** @var \App\Components\CategoriesTable\CategoriesTable @inject */
    public $categoriesTable;

    /** @var AdminFacade @inject */
    public $adminFacade;

    /**
     * Creates the paginator component for handling pagination in the view.
     * 
     * @return MyPaginator - The paginator component
     */
    protected function createComponentMyPaginator(): MyPaginator
    {
        return new MyPaginator();
    }

    // Ready for use when ImagesTable as component are done
    // protected function createComponentImagesTable(): ImagesTable
    // {
    //     return new ImagesTable($this->adminFacade);
    // }

    /**
     * Creates the categories table component
     * 
     * @return CategoriesTableCategoriesTable
     */
    protected function createComponentCategoriesTable(): CategoriesTableCategoriesTable
    {
        return $this->categoriesTable;
    }

    /**
     * Action to delete an image by its ID
     *
     * @param int $id
     */
    public function actionDeleteImage(int $id): void
    {
        $this->adminFacade->deleteImage($id);

        if ($this->isAjax()) {
            $this->sendJson([
                'redirect' => $this->link('Admin:'),
                'flashMessage' => "Image was deleted!"
            ]);
        } else {
            $this->redirect('Admin:');
        }
    }

    /**
     * Render method for the default action
     *
     * @param int $page
     */
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

        $this['myPaginator']->setTotalItems($imageCount);
        $this['myPaginator']->setPage($page);
        $this['myPaginator']->setItemsPerPage(10);
        $this['myPaginator']->setBaseLink($this->link('Admin:default', ['page' => 1]));

        $offset = $this['myPaginator']->getOffset();
        $length = $this['myPaginator']->getLimit();

        $images = $this->adminFacade->showImages($length, $offset);

        $this->template->images = $images;
        $this->template->columns = $columns;
        $this->template->page = $page;

        $this->redrawControl('content');
    }
}
