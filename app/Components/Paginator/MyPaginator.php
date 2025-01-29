<?php

namespace App\Components\Paginator;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class MyPaginator extends Control
{
    /** @var Paginator */
    private $paginator;

    private $baseLink;

    /**
     * Constructor for the MyPaginator component
     * 
     * Initializes a new instance of the Paginator class
     */
    public function __construct()
    {
        $this->paginator = new Paginator();
    }

    /**
     * Set the number of items per page
     * 
     * @param int $itemsPerPage
     */
    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->paginator->setItemsPerPage($itemsPerPage);
    }

    /**
     * Set the total number of items
     * 
     * @param int $totalItems
     */
    public function setTotalItems(int $totalItems): void
    {
        $this->paginator->setItemCount($totalItems);
    }

    /**
     * Set the current page
     * 
     * @param int $currentPage
     */
    public function setPage(int $currentPage): void
    {
        $this->paginator->setPage($currentPage);
    }

    /**
     * Set the base link for pagination
     * 
     * @param string $baseLink
     */
    public function setBaseLink(string $baseLink): void
    {
        $this->baseLink = $baseLink;
    }

    /**
     * Get the limit (number of items per page)
     * 
     * @return int 
     */
    public function getLimit(): int
    {
        return $this->paginator->getLength();
    }

    /**
     * Get the offset (starting position of the current page)
     * 
     * @return int
     */
    public function getOffset(): int
    {
        return $this->paginator->getOffset();
    }

    /**
     * Render the paginator component
     * 
     * Prepare the paginator and base link data for the template and render it
     */
    public function render(): void
    {
        $this->template->paginator = $this->paginator;
        $this->template->baseLink = $this->baseLink;
        $this->template->setFile(__DIR__ . '/myPaginator.latte');
        $this->template->render();

        $this->redrawControl('content');
    }
}
