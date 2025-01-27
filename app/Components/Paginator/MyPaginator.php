<?php

namespace App\Components\Paginator;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class MyPaginator extends Control
{
   /** @var Paginator */
   private $paginator;

   
   private $baseLink;

   public function __construct()
   {
        // $this->baseLink = $baseLink;
       $this->paginator = new Paginator();
       
   }

   public function setItemsPerPage(int $itemsPerPage): void
   {
       $this->paginator->setItemsPerPage($itemsPerPage);
   }

   public function setTotalItems(int $totalItems): void
   {
       $this->paginator->setItemCount($totalItems);
   }

   public function setPage(int $currentPage): void
   {
       $this->paginator->setPage($currentPage);
   }
   public function setBaseLink(string $baseLink): void
   {
       $this->baseLink = $baseLink;
   }

   // Get the limit (number of items per page)
   public function getLimit(): int
   {
       return $this->paginator->getLength();  // Returns items per page
   }

   // Get the offset (starting position of the current page)
   public function getOffset(): int
   {
       return $this->paginator->getOffset();  // Returns the offset
   }

   public function render(): void
   {
       $this->template->paginator = $this->paginator;
       $this->template->baseLink = $this->baseLink;
       $this->template->setFile(__DIR__ . '/myPaginator.latte');
       $this->template->render();

    
        $this->redrawControl('content');
    
   }

   

//    public function getPaginator(): Paginator
//    {
//        return $this->paginator;
//    }
}
