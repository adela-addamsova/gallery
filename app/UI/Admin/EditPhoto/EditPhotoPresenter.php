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

        $photoForm->onSave[] = function () {

            $this->flashMessage('Photo updated successfully!', 'success');
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
        $image = $this->adminFacade->getImage($id);
        $this->template->image = $image;
    }
}
