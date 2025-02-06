<?php

declare(strict_types=1);

namespace App\Components\CategoryForm;

use App\Model\Facades\AdminFacade;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class CategoryForm extends Control
{
    // Event callback triggered when the form is saved
    public array $onSave = [];

    // Edit mode flag to determine if we are editing an existing image or creating a new one
    private bool $isEditMode;

    /** @var AdminFacade @inject */
    public $adminFacade;

    // Holds image data for editing when in edit mode
    private $categoryData = null;

    /**
     * Set the edit mode and optionally provides image data for editing
     * 
     * @param bool $isEditMode - Indicates if edit mode is set
     */

     public function setEditMode(bool $isEditMode, $categoryData = null): void
    {
        $this->isEditMode = $isEditMode;
        $this->categoryData = $categoryData;
    }

    /**
     * Create the form component for photo data
     * 
     * @return Form - The generated form component
     */
    protected function createComponentForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name')
            ->setHtmlAttribute('placeholder', 'Name')
            ->setRequired();
        $form->addText('description', 'Description')
            ->setHtmlAttribute('placeholder', 'Description');
        $form->addText('background_path', 'Background image path')
            ->setHtmlAttribute('placeholder', '/img/macro/img1.webp')
            ->setRequired();

        $form->addSubmit('send', $this->isEditMode ? 'Update' : 'Save');

        if ($this->isEditMode && $this->categoryData) {
            $form->setDefaults([
                'description' => $this->categoryData['description'],
                'name' => $this->categoryData['name'],
                'background_path' => $this->categoryData['background_path'],
            ]);
        }

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    /**
     * Handle the form submission when it is successfully processed
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
     * Render the PhotoForm component
     */
    public function render(): void
    {
        $this->template->isEditMode = $this->isEditMode;
        $this->template->setFile(__DIR__ . '/categoryForm.latte');
        $this->template->render();
    }
}
