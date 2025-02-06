<?php

declare(strict_types=1);

namespace App\UI\Admin\AddCategory;

use App\Components\CategoryForm\CategoryForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\CategoryFormFactory;
use App\UI\Admin\BasePresenter;

class AddCategoryPresenter extends BasePresenter
{
    /**
     * Constructor for AddPhotoPresenter
     * 
     * @param CategoryFormFactory $categoryFormFactory - Factory for creating photo form components
     */
    public function __construct(
        private CategoryFormFactory $categoryFormFactory
    ) {}

    /** @var AdminFacade @inject */
    public $adminFacade;

    /**
     * Creates the category form component
     * 
     * @return CategoryForm
     */
    protected function createComponentCategoryForm(): CategoryForm
    {
        $categoryForm = $this->categoryFormFactory->create();

        $categoryForm->setEditMode(false);

        $categoryForm->onSave[] = function (CategoryForm $categoryForm, $data): void {
            $this->adminFacade->insertCategory($data);
            $this->flashMessage('Category was added successfully!', 'success');
        };

        return $categoryForm;
    }
}
