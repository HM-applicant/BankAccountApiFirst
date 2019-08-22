<?php

namespace App\Transaction;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Transaction extends Model
{
    /** @var User */
    private $user;

    /** @var string */
    private $iban;

    /** @var string */
    private $subject;

    /** @var float */
    private $amount;

    /** @var \DateTime */
    private $createdAt;

    /**
     * Transaction constructor.
     * @param User      $user
     * @param string    $iban
     * @param string    $subject
     * @param float     $amount
     * @param \DateTime $createdAt
     */
    public function __construct(User $user, string $iban, string $subject, float $amount, DateTime $createdAt)
    {
        $this->user      = $user;
        $this->iban      = $iban;
        $this->subject   = $subject;
        $this->amount    = $amount;
        $this->createdAt = $createdAt;

        parent::__construct();
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
