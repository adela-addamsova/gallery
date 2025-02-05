<?php
declare(strict_types=1);

namespace App\Model\Facades;

use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\Mail\SmtpMailer;
use Nette\Mail\Message;

class ContactFacade
{
	public function __construct(
		private LatteFactory $latteFactory,
	) {
	}
	public function sendMessage(string $email, string $name, string $message): void
	{
        $mailer = new SmtpMailer (
            host: 'smtp.gmail.com',
            username: '',
            password: '',
            encryption: 'ssl',
        );

		$latte = $this->latteFactory->create();
		$body = $latte->renderToString(__DIR__ . '/contactEmail.latte', [
			'email' => $email,
			'name' => $name,
			'message' => $message,
		]);
        
		$mail = new Message;
		$mail->addTo('adadaq@seznam.cz')
			->setFrom($email, $name)
			->setSubject('Message from the contact form')
			->setHtmlBody($body);

		$mailer->send($mail);
	}
}
