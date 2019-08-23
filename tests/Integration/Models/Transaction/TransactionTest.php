<?php

namespace Tests\Integration\Models\Transaction;

use App\Transaction\Transaction;
use App\Transaction\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use DateTime;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    /** @var Transaction */
    private $transaction;

    /** @test */
    protected function setUp(): void
    {
        parent::setUp();

        $this->transaction = factory(Transaction::class)->create();
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
        $this->assertSame($this->transaction->getSubject(), 'Test');
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
