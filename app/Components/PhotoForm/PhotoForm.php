<?php

declare(strict_types=1);

namespace App\Components\PhotoForm;

use App\Model\Facades\AdminFacade;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class PhotoForm extends Control
{
    public array $onSave = [];
    private bool $isEditMode;
    private $imageData = null;

    public function __construct(
        private AdminFacade $adminFacade,
    ) {}

    public function setEditMode(bool $isEditMode,  $imageData = null): void
    {
        $this->isEditMode = $isEditMode;
        $this->imageData = $imageData;
    }

    protected function createComponentForm(): Form
    {
        $form = new Form;

        $form->addText('description', 'Description')
            ->setHtmlAttribute('placeholder', 'Description');
        $form->addText('category_id',  'Category ID')
            ->setHtmlAttribute('placeholder', 'Category_ID')
            ->setRequired();
        $form->addText('path', 'Full image path')
            ->setHtmlAttribute('placeholder', '/img/macro/img1.webp')
            ->setRequired();
        $form->addText('thumb_path', 'Thumb path')
            ->setHtmlAttribute('placeholder', '/img/macro/thumbs/img1.webp')
            ->setRequired();

        $form->addSubmit('send', $this->isEditMode ? 'Update' : 'Save');

        if ($this->isEditMode && $this->imageData) {
            $form->setDefaults([
                'description' => $this->imageData['description'],
                'category_id' => $this->imageData['category_id'],
                'path' => $this->imageData['path'],
                'thumb_path' => $this->imageData['thumb_path'],
            ]);
        }

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    public function processForm(Form $form, array $values): void
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
