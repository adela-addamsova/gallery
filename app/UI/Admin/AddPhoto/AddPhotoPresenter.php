<?php

declare(strict_types=1);

namespace App\UI\Admin\AddPhoto;

use App\Components\PhotoForm\PhotoForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\PhotoFormFactory;
use App\UI\Admin\BasePresenter;

class AddPhotoPresenter extends BasePresenter
{
    private PhotoFormFactory $photoFormFactory;
    private AdminFacade $adminFacade;

    public function __construct(
        PhotoFormFactory $photoFormFactory,
        AdminFacade $adminFacade
    ) {
        $this->photoFormFactory = $photoFormFactory;
        $this->adminFacade = $adminFacade;
    }

    protected function createComponentPhotoForm(): PhotoForm
    {
        $photoForm = $this->photoFormFactory->create();

        $photoForm->setEditMode(false);
        

        $photoForm->onSave[] = function () {
            $this->flashMessage('Photo was added successfully!', 'success');
        };

        return $photoForm;
        
    }
}
