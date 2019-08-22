<?php

namespace Tests\Unit;

use App\Transaction\Factory\ZipFileUserTransaction;
use App\Transaction\User;
use Tests\TestCase;
use DateTime;

class ZipFileUserTransactionTest extends TestCase
{
    /** @var ZipFileUserTransaction */
    private $zipFileTransaction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->zipFileTransaction = new ZipFileUserTransaction(
            'api_url',
            'api_key'
        );
    }

    public function testConstructor()
    {
        $transaction = new ZipFileUserTransaction(
            'api_url',
            'api_key'
        );
        $this->assertTrue($transaction instanceof ZipFileUserTransaction);
    }

    public function testGetTransactions()
    {
        $user = new User('Max', 'Mustermann');
        $date = new DateTime('10-10-2019');

        $this->assertCount(
            4,
            $this->zipFileTransaction->getTransactions($user, $date)
        );
    }
}
