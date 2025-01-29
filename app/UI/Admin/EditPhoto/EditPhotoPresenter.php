<?php

declare(strict_types=1);

namespace App\UI\Admin\EditPhoto;

use App\Components\PhotoForm\PhotoForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\PhotoFormFactory;
use Exception;
use Nette\Application\UI\Presenter;

class EditPhotoPresenter extends Presenter
{
    public function __construct(
        private PhotoFormFactory $photoFormFactory
    ) {}


    /** @var AdminFacade @inject */
    public $adminFacade;

    protected function createComponentPhotoForm()
    {
        $id = $this->getParameter('id');
        // $data = $this->adminFacade->getImage($id);

        $photoForm = $this->photoFormFactory->create();

        if ($id) {
            $data = $this->adminFacade->getImage($id);
            $photoForm->setEditMode(true, $data);
        } else {
            $photoForm->setEditMode(false);
        }

        bdump($id);

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
            // $this->redirect('this');  // Optionally redirect after saving
        };

        return $photoForm;
    }


    public function renderDefault($id): void
    {
        $this->template->id = $id;
    }
}
