<?php

namespace App\Transaction;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Transaction extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
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
     *
     * @throws \Exception
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    public function setUser(User $user)
    {
        $this->user_id = $user->getId();

        return $this;
    }

    public function setIban(string $iban)
    {
        $this->iban = $iban;

        return $this;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;

        return $this;
    }
}
