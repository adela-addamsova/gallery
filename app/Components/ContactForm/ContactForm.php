<?php

namespace App\Components\ContactForm;

use App\Model\Facades\ContactFacade;
use Contributte\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Http\Request;
use Nette\Http\Session;
use Nette\Utils\ArrayHash;

class ContactForm extends Control
{
    private ContactFacade $facade;
    /** @var Translator */
    private $translator;
    private Request $httpRequest;
    private Session $session;

    public function __construct(ContactFacade $facade, Translator $translator, Request $httpRequest, Session $session)
    {
        $this->facade = $facade;
        $this->translator = $translator;
        $this->httpRequest = $httpRequest;
        $this->session = $session;
    }

    public function createComponentContactForm(): Form
    {
        $form = new Form;

        $form->addText('name')
            ->setRequired($this->translator->translate("contact_form.required_name"))
            ->setHtmlAttribute('placeholder', $this->translator->translate("contact_form.name"));

        $form->addText('subject')
            ->setRequired($this->translator->translate("contact_form.required_subject"))
            ->setHtmlAttribute('placeholder', $this->translator->translate("contact_form.subject"));

        $form->addEmail('email')
            ->setRequired($this->translator->translate("contact_form.required_email"))
            ->addRule(Form::EMAIL, $this->translator->translate("contact_form.valid_email"))
            ->setHtmlAttribute('placeholder', $this->translator->translate("contact_form.email"));

        $form->addTextArea('message')
            ->setRequired($this->translator->translate("contact_form.required_message"))
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

    private const MAX_MESSAGES_PER_IP = 3;
    public function contactFormSucceeded(Form $form, ArrayHash $data): void
    {
        $ipAddress = $this->getPresenter()->getHttpRequest()->getRemoteAddress();
        $sessionSection = $this->getPresenter()->session->getSection('contactForm');

        $currentTimestamp = time();
        $oneWeekAgo = strtotime('-1 week', $currentTimestamp);

        $messageData = $sessionSection[$ipAddress] ?? ['count' => 0, 'timestamp' => $currentTimestamp];

        if ($messageData['timestamp'] < $oneWeekAgo) {
            $messageData['count'] = 0;
            $messageData['timestamp'] = $currentTimestamp;
        }

        if ($messageData['count'] >= self::MAX_MESSAGES_PER_IP) {
            $this->template->message = $this->translator->translate("contact_form.error_limit_message");
            $this->redrawControl('contactFormSnippet');
            return;
        }

        try {
            $this->facade->sendMessage($data->email, $data->name, $data->message);

            $this->template->message = $this->translator->translate("contact_form.success_message");
            $messageData['count'] += 1;
            $messageData['timestamp'] = $currentTimestamp;
            $sessionSection[$ipAddress] = $messageData;
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
