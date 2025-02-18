<?php

declare(strict_types=1);

namespace App\UI\Admin\EditCategory;

use App\Components\CategoryForm\CategoryForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\CategoryFormFactory;
use App\UI\Admin\BasePresenter;

class EditCategoryPresenter extends BasePresenter
{
    /**
     * Constructor
     *
     * @param CategoryFormFactory $categoryFormFactory
     */
    public function __construct(
        private CategoryFormFactory $categoryFormFactory
    ) {}

    /** @var AdminFacade @inject */
    public $adminFacade;

    /**
     * Create the PhotoForm component
     *
     * @return CategoryForm
     */
    protected function createComponentCategoryForm()
    {
        $id = $this->getParameter('id');

        $categoryForm = $this->categoryFormFactory->create();

        if ($id) {
            $data = $this->adminFacade->getCategory($id);
            $categoryForm->setEditMode(true, $data);
        } else {
            $categoryForm->setEditMode(false);
        }

        $categoryForm->onSave[] = function (CategoryForm $categoryForm, $updateData) {
            $id = $this->getParameter('id');
            $updateData =
                [
                    'id' => $id,
                    'description' => $updateData['description'],
                    'name' => $updateData['name'],
                    'background_path' => $updateData['background_path'],
                    'icon_path' => $updateData['icon_path'],
                ];
            $this->adminFacade->updateCategory($updateData, $id);
            $this->flashMessage('Category was updated successfully!', 'success');
        };

        return $categoryForm;
    }

    /**
     * Render the default template and passes the photo ID
     *
     * @param int $id
     */
    public function renderDefault($id): void
    {
        $this->template->id = $id;
        $category = $this->adminFacade->getCategory($id);
        $this->template->category = $category;
    }
}
