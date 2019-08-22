<?php

namespace App\Transaction\Factory;

use App\Transaction\User;
use DateTime;

/**
 * Interface for accessing a banking server api
 */
interface UserTransaction
{
    public function __construct(string $url, string $apiKey);
    public function getTransactions(User $user, DateTime $date);
}
