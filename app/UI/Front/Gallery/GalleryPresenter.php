<?php

declare(strict_types=1);

namespace App\UI\Front\Gallery;

use App\Model\Facades\ImageFacade;
use Nette;


final class GalleryPresenter extends Nette\Application\UI\Presenter
{
    /** @var ImageFacade @inject */
    public $imageFacade;

    public function renderDefault(string $category, int $page = 1): void
    {
        // Fetch images from the selected category
        // $images = $this->imageFacade->getImagesByCategory($category);
        // $this->template->images = $images;
        $this->template->category = $category;

        // We'll find the total number of published articles
		$imageCount = $this->imageFacade->countImagesByCategory($category);

		// We'll make the Paginator instance and set it up
		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemCount($imageCount); // total articles count
		$paginator->setItemsPerPage(9); // items per page
		$paginator->setPage($page); // actual page number

		// We'll find a limited set of articles from the database based on Paginator's calculations
		$images = $this->imageFacade->getImagesByCategory($category, $paginator->getLength(), $paginator->getOffset());

		// which we pass to the template
		$this->template->images = $images;
		// and also Paginator itself to display paging options
		$this->template->paginator = $paginator;
    }
}
