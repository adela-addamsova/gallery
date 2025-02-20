<?php

namespace App\Components\ContactForm;

use App\Model\Facades\ContactFacade;
use Contributte\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class ContactForm extends Control
{
    private ContactFacade $facade;
    /** @var Translator */
    private $translator;

    public function __construct(ContactFacade $facade, Translator $translator)
    {
        $this->facade = $facade;
        $this->translator = $translator;
    }

    public function createComponentContactForm(): Form
    {
        $form = new Form;

        $form->addText('name')
            ->setRequired('Please enter your name.')
            ->setHtmlAttribute('placeholder', $this->translator->translate("contact_form.name"));

        $form->addText('subject')
            ->setRequired('Please enter your name.')
            ->setHtmlAttribute('placeholder', $this->translator->translate("contact_form.subject"));

        $form->addEmail('email')
            ->setRequired('Please enter your email.')
            ->addRule(Form::EMAIL, 'Please enter a valid email address.')
            ->setHtmlAttribute('placeholder', $this->translator->translate("contact_form.email"));

        $form->addTextArea('message')
            ->setRequired('Please enter your message.')
            ->setAttribute('rows', 4)
            ->setHtmlAttribute('placeholder', $this->translator->translate("contact_form.message"))
            ->setHtmlAttribute('class', "form-control");

        $locale = $this->getPresenter()->getParameter('locale');

        if ($locale === 'cs') {
            $form->setAction($this->getPresenter()->link('About:about', ['locale' => 'cs']));
        } else {
            $form->setAction($this->getPresenter()->link('About:about', ['locale' => 'en']));
        }

        $form->addSubmit('send', $this->translator->translate("contact_form.send"));

        $form->onSuccess[] = [$this, 'contactFormSucceeded'];

        return $form;
    }

    /**
     * Handles form submission
     * @param Form $form
     * @param array $values
     */
    public function contactFormSucceeded(Form $form, ArrayHash $data): void
    {
        try {
            $this->facade->sendMessage($data->email, $data->name, $data->message);

            $this->template->message = $this->translator->translate("contact_form.success_message");

            $form->setValues([], true);
        } catch (\Exception $e) {
            $this->template->message = $this->translator->translate("contact_form.error_message");
        }

        $this->redrawControl('contactFormSnippet');
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
