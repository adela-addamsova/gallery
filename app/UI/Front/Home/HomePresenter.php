<?php

declare(strict_types=1);

namespace App\UI\Front\Home;

use App\Front\BasePresenter;
use App\Components\Navbar;
use App\Model\Facades\ImageFacade;
use Contributte\Translation\Translator;

final class HomePresenter extends BasePresenter
{
    /** @var ImageFacade @inject */
    public $imageFacade;
    /** @var Translator */
    public $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Creates the navbar component
     * @return Navbar
     */
    protected function createComponentNavbar(): Navbar
    {
        return new Navbar($this->translator, $this->template->locale);
    }

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
