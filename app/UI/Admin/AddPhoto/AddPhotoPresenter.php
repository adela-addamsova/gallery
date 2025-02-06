<?php

declare(strict_types=1);

namespace App\UI\Admin\AddPhoto;

use App\Components\PhotoForm\PhotoForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\PhotoFormFactory;
use App\UI\Admin\BasePresenter;

class AddPhotoPresenter extends BasePresenter
{
    /**
     * Constructor for AddPhotoPresenter
     * 
     * @param PhotoFormFactory $photoFormFactory - Factory for creating photo form components
     */
    public function __construct(
        private PhotoFormFactory $photoFormFactory
    ) {}

    /** @var AdminFacade @inject */
    public $adminFacade;

    /**
     * Creates the photo form component
     * 
     * @return PhotoForm
     */
    protected function createComponentPhotoForm(): PhotoForm
    {
        $photoForm = $this->photoFormFactory->create();

        $photoForm->setEditMode(false);

        $photoForm->onSave[] = function (PhotoForm $photoForm, $data): void {
            $this->adminFacade->insertImage($data);
            $this->flashMessage('Photo was added successfully!', 'success');
        };

        return $photoForm;
    }
}
