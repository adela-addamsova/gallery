<?php

namespace App\Components;

use Nette\Application\UI\Control;
use Contributte\Translation\Translator;

class Navbar extends Control
{
    /** @var Translator */
    private $translator;

    /** @var string */
    private $locale;

    public function __construct(Translator $translator, string $locale)
    {
        $this->translator = $translator;
        $this->locale = $locale;
    }

    public function render()
    {
        // Pass variables to the template
        $this->template->locale = $this->locale;
        $this->template->setTranslator($this->translator);

        $this->template->setFile(__DIR__ . '/navbar.latte');
        $this->template->render();
    }
}

