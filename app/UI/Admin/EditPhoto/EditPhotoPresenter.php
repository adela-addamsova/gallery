<?php

declare(strict_types=1);

namespace App\UI\Admin\EditPhoto;

use App\Components\PhotoForm\PhotoForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\PhotoFormFactory;
use App\UI\Admin\BasePresenter;

class EditPhotoPresenter extends BasePresenter
{
    /**
     * Constructor
     *
     * @param PhotoFormFactory $photoFormFactory
     */
    public function __construct(
        private PhotoFormFactory $photoFormFactory
    ) {}

    /** @var AdminFacade @inject */
    public $adminFacade;

    /**
     * Create the PhotoForm component
     *
     * @return PhotoForm
     */
    protected function createComponentPhotoForm()
    {
        $id = $this->getParameter('id');

        $photoForm = $this->photoFormFactory->create();

        if ($id) {
            $data = $this->adminFacade->getImage($id);
            $photoForm->setEditMode(true, $data);
        } else {
            $photoForm->setEditMode(false);
        }

        $photoForm->onSave[] = function (PhotoForm $photoForm, $updateData) {
            $id = $this->getParameter('id');
            $updateData =
                [
                    'id' => $id,
                    'description' => $updateData['description'],
                    'category_id' => $updateData['category_id'],
                    'path' => $updateData['path'],
                    'thumb_path' => $updateData['thumb_path'],
                ];
            $this->adminFacade->updateImage($updateData, $id);
            $this->flashMessage('Photo updated successfully!', 'success');
            bdump($updateData);
        };

        return $photoForm;
    }

    /**
     * Render the default template and passes the photo ID
     *
     * @param int $id
     */
    public function renderDefault($id): void
    {
        $this->template->id = $id;
    }
}
