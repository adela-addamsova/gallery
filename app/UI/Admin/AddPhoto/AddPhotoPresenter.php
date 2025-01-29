<?php

declare(strict_types=1);

namespace App\UI\Admin\AddPhoto;

use App\Components\PhotoForm\PhotoForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\PhotoFormFactory;
use Nette\Application\UI\Presenter;

class AddPhotoPresenter extends Presenter
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
    protected function createComponentPhotoForm()
    {
        $photoForm = $this->photoFormFactory->create();

        $photoForm->setEditMode(false);

        $photoForm->onSave[] = function (): void {
            $this->flashMessage('Photo added successfully!', 'success');
        };

        return $photoForm;
    }
}
