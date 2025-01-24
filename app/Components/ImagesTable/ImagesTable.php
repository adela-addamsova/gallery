<?php

declare(strict_types=1);

namespace App\Components\ImagesTable;

use App\Model\Facades\AdminFacade;
use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class ImagesTable extends Control {
  
    private AdminFacade $adminFacade;
    public function __construct(AdminFacade $adminFacade)
    {
        $this->adminFacade = $adminFacade;
    }

    public function render(): void
    {
        $columns = [
            'description' => 'Description',
            'path' => 'Image path',
            'thumb_path' => 'Thumb path',
            'category_id' => 'Category ID',
            'uploaded_at' => 'Uploaded at'
        ];

        $images = $this->adminFacade->showImages();
        $this->template->images = $images;
        $this->template->columns = $columns;
        $this->template->setFile(__DIR__ . '/ImagesTable.latte');
        $this->template->render();
    }
}