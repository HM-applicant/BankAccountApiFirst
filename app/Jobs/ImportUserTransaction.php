<?php

namespace App\Jobs;

use App\Transaction\Factory\UserTransaction;
use App\Transaction\User;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class ImportUserTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var User */
    protected $user;

    /** @var DateTime */
    protected $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, DateTime $date)
    {
        $this->user = $user;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserTransaction $service)
    {
        $transactions = $service->getTransactions($this->user, $this->date);
        $insertTransactions = new Collection($transactions);

        DB::table('transactions')->insert($insertTransactions->toArray());
    }
}
