<?php

declare(strict_types=1);

namespace App\UI\Admin\AddPhoto;

use App\Components\PhotoForm\PhotoForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\PhotoFormFactory;
use Nette\Application\UI\Presenter;

class AddPhotoPresenter extends Presenter
{
    public function __construct(
		private PhotoFormFactory $photoFormFactory
	) {
	}


    /** @var AdminFacade @inject */
    public $adminFacade;

    protected function createComponentPhotoForm()
    {

        $photoForm = $this->photoFormFactory->create();

        $photoForm->setEditMode(false);

		$photoForm->onSave[] = function (PhotoForm $photoForm, $data) {
			$this->flashMessage('Photo added successfuly!', 'success');
		};

        


		return $photoForm;
    }
}
