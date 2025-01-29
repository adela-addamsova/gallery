<?php

declare(strict_types=1);

namespace App\Components\PhotoForm;

use App\Model\Facades\AdminFacade;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class PhotoForm extends Control
{
    // Event callback triggered when the form is saved
    public array $onSave = [];

    // Edit mode flag to determine if we are editing an existing image or creating a new one
    private bool $isEditMode;

    // Holds image data for editing when in edit mode
    private $imageData = null;

    /** @var AdminFacade @inject */
    public $adminFacade;

    /**
     * Sets the edit mode and optionally provides image data for editing
     * 
     * @param bool $isEditMode - Indicates if edit mode is set
     * @param mixed $imageData - Image data to be used for editing
     */
    public function setEditMode(bool $isEditMode, $imageData = null): void
    {
        $this->isEditMode = $isEditMode;
        $this->imageData = $imageData;
    }

    /**
     * Creates the form component for photo data
     * 
     * @return Form - The generated form component
     */
    protected function createComponentForm(): Form
    {
        $form = new Form;

        $form->addText('description', 'Description')
            ->setHtmlAttribute('placeholder', 'Description');
        $form->addText('category_id', 'Category ID')
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

    /**
     * Handles the form submission when it is successfully processed
     * 
     * @param array $values - The submitted form data
     */
    public function processForm(array $values): void
    {
        // Trigger all the onSave callbacks registered by the component or external code
        foreach ($this->onSave as $callback) {
            $callback($this, $values);
        }
    }

    /**
     * Renders the PhotoForm component
     */
    public function render(): void
    {
        $this->template->isEditMode = $this->isEditMode;
        $this->template->setFile(__DIR__ . '/photoForm.latte');
        $this->template->render();
    }
}
