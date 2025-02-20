<?php declare(strict_types = 1);

namespace App\Front;

use Nette;
use Nette\Application\BadRequestException;

class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Localization\ITranslator @inject */
    public $translator;

    /** @var array Supported locales */
    private $supportedLocales = ['en', 'cs'];

    protected function startup()
    {
        parent::startup();

        $locale = $this->getParameter('locale', 'en');

        if (!in_array($locale, $this->supportedLocales)) {
            throw new BadRequestException("Invalid locale: $locale", Nette\Http\IResponse::S404_NotFound);
        }

        $this->translator->setLocale($locale);
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
}
