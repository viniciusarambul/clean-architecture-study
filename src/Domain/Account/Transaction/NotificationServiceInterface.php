<?php

namespace App\Domain\Account\Transaction;

use App\Adapter\Service\NotificationService;

interface NotificationServiceInterface
{
    public function notify(): bool;
}