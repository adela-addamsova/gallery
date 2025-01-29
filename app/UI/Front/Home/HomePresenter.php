<?php

declare(strict_types=1);

namespace App\UI\Front\Home;

use App\Model\Facades\ImageFacade;
use Nette;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    /** @var ImageFacade @inject */
    public $imageFacade;

    /**
     * Render the home page
     *
     * @return void
     */
    public function renderDefault(): void
    {
        $categories = $this->imageFacade->getImageCategories();

        $latestImages = [];

        foreach ($categories as $category) {
            $latestImage = $this->imageFacade->getLatestImage($category->id);

            if ($latestImage !== null) {
                $latestImages[$category->id] = $latestImage;
            }
        }

        $this->template->categories = $categories;
        $this->template->latestImages = $latestImages;
    }
}
