<?php

namespace App\Providers;

use App\Transaction\Factory\UserTransaction;
use App\Transaction\Factory\ZipFileUserTransaction;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserTransaction::class,
            ZipFileUserTransaction::class
        );
        $this->app->bind(ZipFileUserTransaction::class, function () {
            return new ZipFileUserTransaction(
                config('services.transaction_import.url'),
                config('services.transaction_import.key')
            );
        });
    }
}
