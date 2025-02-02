<?php

declare(strict_types=1);

namespace App\UI\Front\Gallery;

use App\Components\Paginator\MyPaginator;
use App\Model\Facades\ImageFacade;
use Nette\Application\UI\Presenter;

final class GalleryPresenter extends Presenter
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
        $itemsCount = $this->imageFacade->countImagesByCategory($category);

        $this['myPaginator']->setTotalItems($itemsCount);
        $this['myPaginator']->setPage($page);
        $this['myPaginator']->setItemsPerPage(9);
        $this['myPaginator']->setBaseLink($this->link('this', ['page' => 1]));

        $offset = $this['myPaginator']->getOffset();
        $length = $this['myPaginator']->getLimit();
        $items = $this->imageFacade->getImagesByCategory($category, $length, $offset);

        $this->template->images = $items;
        $this->template->itemsCount = $itemsCount;
        $this->template->page = $page;
        $this->template->category = $category;

        $this->redrawControl('content');
    }
}
