<?php

namespace Tests\Unit;

use App\Transaction\Transaction;
use App\Transaction\User;
use Tests\TestCase;
use DateTime;

class TransactionTest extends TestCase
{
    /** @var Transaction */
    private $transaction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transaction = new Transaction(
            (new User('Max', 'Mustermann')),
            'DE201234567890',
            'stuff',
            100,
            (new DateTime('10-10-2019'))
        );
    }

    public function testConstructor()
    {
        $transaction = new Transaction(
            (new User('Max', 'Mustermann')),
            'DE201234567890',
            'stuff',
            100,
            (new DateTime('10-10-2019'))
        );
        $this->assertTrue($transaction instanceof Transaction);
    }

    public function testGetUser()
    {
        $user = $this->transaction->getUser();
        $this->assertTrue($user instanceof User);
    }

    public function testGetIban()
    {
        $this->assertSame($this->transaction->getIban(), 'DE201234567890');
    }

    public function testGetSubject()
    {
        $this->assertSame($this->transaction->getSubject(), 'stuff');
    }

    public function testGetAmmount()
    {
        $this->assertSame($this->transaction->getAmount(), 100.0);
    }

    public function testGetDate()
    {
        $this->assertEquals($this->transaction->getCreatedAt(), (new DateTime('10-10-2019')));
    }
}
