<?php

namespace App\Console\Commands;

use App\Transaction\Factory\UserTransaction;
use App\Transaction\User;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ImportTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports bank transactions for all users';

    protected $transactionRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserTransaction $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $this->importAllUsers();
    }

    /**
     * imports transactions from all users in the database
     *
     * @throws \Exception
     */
    public function importAllUsers()
    {
        $users = User::all();

        foreach ($users as $user) {
            $this->importUser($user);
        }
    }

    /**
     * import Transactions from specific user
     *
     * @param User $user
     * @throws \Exception
     */
    public function importUser(User $user)
    {
        $transactions = $this->transactionRepository->getTransactions($user, (new DateTime('now')));
        $insertTransactions = new Collection($transactions);

        DB::table('transactions')->insert($insertTransactions->toArray());
    }
}
