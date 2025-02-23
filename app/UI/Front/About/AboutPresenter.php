<?php

declare(strict_types=1);

namespace App\UI\Front\About;

use App\Front\BasePresenter;
use App\Components\ContactForm\ContactForm;
use App\Components\Navbar;
use Contributte\Translation\Translator;

final class AboutPresenter extends BasePresenter
{
    /** @var \App\Model\Facades\ContactFacade @inject */
    public $contactFacade;
    /** @var Translator */
    public $translator;
    private ContactForm $contactForm;

    public function __construct(ContactForm $contactForm, Translator $translator)
    {
        $this->contactForm = $contactForm;
        $this->translator = $translator;
    }

    /**
     * Creates the contact form component
     * @return ContactForm
     */
    protected function createComponentContactForm(): ContactForm
    {
        $contactForm = new ContactForm($this->contactFacade, $this->translator, $this->getHttpRequest(), $this->getSession());

        return $contactForm;
    }

    /**
     * Creates the navbar component
     * @return Navbar
     */
    protected function createComponentNavbar(): Navbar
    {
        return new Navbar($this->translator, $this->template->locale);
    }
}
