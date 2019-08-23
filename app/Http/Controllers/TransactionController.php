<?php

namespace App\Http\Controllers;

use App\Jobs\ImportUserTransaction;
use App\Transaction\Factory\UserTransaction;
use App\Transaction\User;
use DateTime;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionRepository;

    /**
     * TransactionController constructor.
     * @param $transactionRepository
     */
    public function __construct(UserTransaction $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse

     * @throws \Exception
     */
    public function import(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $date = new DateTime($request->date);

        ImportUserTransaction::dispatch($user, $date);

        return response()->json(null, 200);
    }
}
