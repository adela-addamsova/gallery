<?php

namespace App\Components\ContactForm;

use App\Model\Facades\ContactFacade;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class ContactForm extends Control
{
    /**
     * @return Form
     */

    private ContactFacade $facade;

    public function __construct(ContactFacade $facade)
    {
        $this->facade = $facade;
    }

    public function createComponentContactForm($callback): Form
    {
        $form = new Form;

        $form->addText('name')
            ->setRequired('Please enter your name.')
            ->setDefaultValue("Your Name");

        $form->addText('subject')
            ->setRequired('Please enter your name.')
            ->setDefaultValue("Subject");

        $form->addEmail('email')
            ->setRequired('Please enter your email.')
            ->addRule(Form::EMAIL, 'Please enter a valid email address.')
            ->setDefaultValue("Your Email");

        $form->addTextArea('message')
            ->setRequired('Please enter your message.')
            ->setAttribute('rows', 4)
            ->setDefaultValue("Message")
            ->setHtmlAttribute('class', "form-control");

        $form->addSubmit('send', 'Send Message');

        $form->onSuccess[] = [$this, 'contactFormSucceeded'];

        return $form;
    }

    /**
     * Handles form submission
     * @param Form $form
     * @param array $values
     */
    public function contactFormSucceeded(ArrayHash $data): void
    {
        $this->facade->sendMessage($data->email, $data->name, $data->message);
        ob_start();
        $this->template->setFile(__DIR__ . '/contactForm.latte');
        $this->template->render();
        $formHtml = ob_get_clean();

        // Send AJAX response with the updated form HTML and success message
        $this->getPresenter()->payload->successMessage = 'Your message has been sent. Thank you!';
        $this->getPresenter()->payload->formHtml = $formHtml;
        $this->getPresenter()->sendPayload();
    }

    /**
     * Render the ContactForm component
     */
    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/contactForm.latte');
        $this->template->render();
    }
}
