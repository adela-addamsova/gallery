<?php declare(strict_types = 1);

namespace App\Front;

use Nette;
use Contributte;


class BasePresenter extends Nette\Application\UI\Presenter
{

	/** @var Nette\Localization\ITranslator @inject */
	public $translator;

	/** @var Contributte\Translation\LocalesResolvers\Session @inject */
	public $translatorSessionResolver;



    protected function startup()
    {
		$locale = $this->getParameter('locale', 'en'); // Default to 'en' if no locale is set
		$this->translator->setLocale($locale);
		parent::startup();
    }

	public function beforeRender()
{
    parent::beforeRender();
    
    // Pass the locale to the template
    $this->template->locale = $this->translator->getLocale();
    
    // Optionally, set the translator in the template
    $this->template->setTranslator($this->translator);
}

public function linkWithLocale(string $destination, $args = []): string
{
    // Get the current parameters of the URL
    $params = $this->getParameters();

    // Ensure the selected locale is set in the URL
    $args['locale'] = $args['locale'] ?? $this->getParameter('locale', 'en'); // Default to 'en' if no locale is set

    // Return the URL with the new locale
    return $this->link($destination, $args);
}

	public function renderDefault(): void
	{
		$this->translator->translate('domain.message');
		$prefixedTranslator = $this->translator->createPrefixedTranslator('domain');
		$prefixedTranslator->translate('message');
	}
    
}