<?php

declare(strict_types=1);

namespace App\UI\Admin;

use Nette\Application\UI\Presenter;

class BasePresenter extends Presenter
{
    private $allowedIps = ['::1'];

    /**
     * Checks whether the client's IP is allowed to access the admin
     */
    public function startup()
    {
        parent::startup();

        $clientIp = $_SERVER['REMOTE_ADDR'];
        $isAllowed = false;

        if (in_array($clientIp, $this->allowedIps)) {
            $isAllowed = true;
        } else {
            $this->error('Access denied', 403);
        }
    }
}
