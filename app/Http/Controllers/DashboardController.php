<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Income;
use App\Models\Outcome;
use Carbon\Carbon;
use App\Services\IncomeService;
use App\Services\OutcomeService;
use App\Utils\Utils;


class DashboardController extends Controller
{
    public function __construct(IncomeService $incomeService,OutcomeService $outcomeService)
    {
        $this->incomeService = $incomeService;
        $this->outcomeService = $outcomeService;
    }

    public function index(){
       
        $currentDate = Carbon::now();
        $firstDayOfMonth = $currentDate->startOfMonth()->format('Y-m-d H:i:s');
        $lastDayOfMonth = $currentDate->endOfMonth()->format('Y-m-d H:i:s');

        $requestOutcome = new Request(['month' => true,'status'=>'completed']);
        $requestIncome = new Request(['month' => true]);
        $incomes = $this->incomeService->getAll($requestIncome);
        $outcomes = $this->outcomeService->getAll($requestOutcome);

        $incomesTotal = $incomes->sum('amount');
        $outcomesTotal = $outcomes->sum('amount');
        $balance = $incomesTotal - $outcomesTotal;

        $incomes->transform(function ($item) {
            $item['amount'] = Utils::publishMoney($item['amount']);
            return $item;
        });
        
        $outcomes->transform(function ($item) {
            $item['amount'] = Utils::publishMoney($item['amount']);
            return $item;
        });
        
        return Inertia::render('Dashboard',[
            'incomesTotal' => Utils::publishMoney($incomesTotal),
            'outcomesTotal' => Utils::publishMoney($outcomesTotal),
            'balance' => Utils::publishMoney($balance),
            'incomes' => $incomes,
            'outcomes' => $outcomes,
        ]);
    }
}
