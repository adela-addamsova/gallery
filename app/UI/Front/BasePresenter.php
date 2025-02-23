<?php 

declare(strict_types = 1);

namespace App\Front;

use Nette;
use Nette\Application\BadRequestException;
use Nette\Http\Session;
use Nette\Http\SessionSection;

class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Localization\ITranslator @inject */
    public $translator;

    /** @var array Supported locales */
    private $supportedLocales = ['en', 'cs'];

    /** @var Session @inject */
    public $session;

    protected function startup()
    {
        parent::startup();

        $locale = $this->getParameter('locale', 'en');

        if (!in_array($locale, $this->supportedLocales)) {
            throw new BadRequestException("Invalid locale: $locale", Nette\Http\IResponse::S404_NotFound);
        }

        $this->translator->setLocale($locale);

        $this->session->start();
    }

    public function beforeRender()
    {
        parent::beforeRender();

        $this->template->locale = $this->translator->getLocale();

        $this->template->setTranslator($this->translator);
    }

    public function linkWithLocale(string $destination, $args = []): string
    {
        $params = $this->getParameters();

        $args['locale'] = $args['locale'] ?? $this->getParameter('locale', 'en');

        return $this->link($destination, $args);
    }

    /**
     * Set a value in the session
     *
     * @param string $name The session section name
     * @param string $key The key within the session section
     * @param mixed $value The value to store
     */
    public function setSessionValue(string $name, string $key, $value): void
    {
        $section = $this->session->getSection($name);
        $section->$key = $value;
    }

    /**
     * Get a value from the session
     *
     * @param string $name The session section name
     * @param string $key The key within the session section
     * @return mixed The stored value or null if it doesn't exist
     */
    public function getSessionValue(string $name, string $key)
    {
        $section = $this->session->getSection($name);
        return $section->$key ?? null;
    }
}
