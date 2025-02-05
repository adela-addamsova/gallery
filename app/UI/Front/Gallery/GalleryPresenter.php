<?php

declare(strict_types=1);

namespace App\UI\Front\Gallery;

use App\Components\Navbar;
use App\Front\BasePresenter;
use App\Components\Paginator\MyPaginator;
use App\Model\Facades\ImageFacade;

final class GalleryPresenter extends BasePresenter
{
    /** @var ImageFacade @inject */
    public $imageFacade;

    /**
     * Create the MyPaginator component
     *
     * @return MyPaginator
     */
    protected function createComponentMyPaginator(): MyPaginator
    {
        return new MyPaginator();
    }

    /**
     * Render the gallery
     *
     * @param int $page
     * @param string $category
     */
    public function renderDefault(int $page = 1, string $category = 'default'): void
    {
        $this->template->categories = $this->imageFacade->getImageCategories();
        $this->template->activeCategory = $category;

        $itemsCount = $this->imageFacade->countImagesByCategory($category);

        $this['myPaginator']->setTotalItems($itemsCount);
        $this['myPaginator']->setPage($page);
        $this['myPaginator']->setItemsPerPage(9);
        // Inside your presenter action

        // Get the current locale from the request
        $locale = $this->getParameter('locale');

        // Set the base link for the paginator, including the locale and page parameters
        $this['myPaginator']->setBaseLink($this->link('this', [
            'locale' => $locale,
            'page' => 1
        ]));


        $offset = $this['myPaginator']->getOffset();
        $length = $this['myPaginator']->getLimit();
        $items = $this->imageFacade->getImagesByCategory($category, $length, $offset);

        $this->template->images = $items;
        $this->template->itemsCount = $itemsCount;
        $this->template->page = $page;
        $this->template->category = $category;

        $this->redrawControl('content');
    }

     /**
     * Creates the navbar component for all pages
     * @return Navbar
     */
    protected function createComponentNavbar(): Navbar
    {
        return new Navbar($this->translator, $this->template->locale);
    }
}
