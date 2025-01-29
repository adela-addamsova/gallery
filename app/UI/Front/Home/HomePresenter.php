<?php

declare(strict_types=1);

namespace App\UI\Front\Home;

use App\Model\Facades\ImageFacade;
use Nette;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    /** @var ImageFacade @inject */
    public $imageFacade;
    public function renderDefault(): void
{
    // Fetch categories from the 'categories' table
    $categories = $this->imageFacade->getImageCategories();

    // Create an array to store the latest image for each category
    $latestImages = [];

    // Fetch the latest image for each category
    foreach ($categories as $category) {
        $latestImage = $this->imageFacade->getLatestImage($category->id);

        if ($latestImage !== null) {
            $latestImages[$category->id] = $latestImage;
        }
    }

    // Pass categories and latest images to the template
    $this->template->categories = $categories;
    $this->template->latestImages = $latestImages;
}

}
