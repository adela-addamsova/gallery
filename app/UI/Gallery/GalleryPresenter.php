<?php

declare(strict_types=1);

namespace App\UI\Gallery;

use App\Model\Facades\ImageFacade;
use Nette;


final class GalleryPresenter extends Nette\Application\UI\Presenter
{
    /** @var ImageFacade @inject */
    public $imageFacade;

    public function renderDefault(string $category): void
    {
        // Fetch images from the selected category
        $images = $this->imageFacade->getImagesByCategory($category);
        $this->template->images = $images;
        $this->template->category = $category; // To display category name in the template
        $categories = $this->imageFacade->getImageCategories();
        $this->template->categories = $categories;
    }
}