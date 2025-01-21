<?php

declare(strict_types=1);

namespace App\UI\Home;

use App\Model\Facades\ImageFacade;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    private ImageFacade $imageFacade;
    public function __construct(ImageFacade $imageFacade)
    {
        parent::__construct();
        $this->imageFacade = $imageFacade;
    }

    public function renderDefault(): void
{
    // Fetch categories from the 'categories' table
    $categories = $this->imageFacade->getImageCategories();

    // Create an array to store the latest image for each category
    $latestImages = [];

    // Fetch the latest image for each category
    foreach ($categories as $category) {
        $latestImages[$category->id] = $this->imageFacade->getLatestImage($category->id);
    }

    // Pass categories and latest images to the template
    $this->template->categories = $categories;
    $this->template->latestImages = $latestImages;
}

}
