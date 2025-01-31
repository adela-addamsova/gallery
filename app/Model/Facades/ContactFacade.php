<?php
declare(strict_types=1);

namespace App\Model\Facades;

use Nette\Mail\SmtpMailer;
use Nette\Mail\Message;

class ContactFacade
{
	public function sendMessage(string $email, string $name, string $message): void
	{
        $mailer = new SmtpMailer (
            host: 'smtp.gmail.com',
            username: '',
            password: '',
            encryption: 'ssl',
        );
        
		$mail = new Message;
		$mail->addTo('adadaq@seznam.cz')
			->setFrom($email, $name)
			->setSubject('Message from the contact form')
			->setBody($message);

		$mailer->send($mail);
	}
}
