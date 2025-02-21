<?php

declare(strict_types=1);

namespace App\Components\PhotoForm;

use App\Model\Facades\AdminFacade;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Utils\ArrayHash;

class PhotoForm extends Control
{
    public array $onSave = [];
    private bool $isEditMode;
    private $imageData = null;  

    /** @var AdminFacade @inject */
    public $adminFacade;

    public function setEditMode(bool $isEditMode, $imageData = null): void
    {
        $this->isEditMode = $isEditMode;
        $this->imageData = $imageData;
    }

    protected function createComponentForm(): Form
    {
        $form = new Form;

        $form->addText('description', 'Description')
            ->setHtmlAttribute('placeholder', 'Description');
        $form->addText('category_id', 'Category ID')
            ->setHtmlAttribute('placeholder', 'Category_ID')
            ->setRequired();
        $form->addUpload('image', 'Image')
            ->setRequired($this->isEditMode ? false : 'Please upload an image.');
        $form->addSubmit('send', $this->isEditMode ? 'Update' : 'Save');

        if ($this->isEditMode && $this->imageData) {
            $form->setDefaults([
                'description' => $this->imageData['description'],
                'category_id' => $this->imageData['category_id'],
            ]);
        }

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    public function processForm(Form $form, ArrayHash $values): void
    {
        foreach ($this->onSave as $callback) {
            $callback($this, $values);
        }
    }

    public function render(): void
    {
        $this->template->isEditMode = $this->isEditMode;
        $this->template->setFile(__DIR__ . '/photoForm.latte');
        $this->template->render();
    }
}
