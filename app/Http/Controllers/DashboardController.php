<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Income;
use App\Models\Outcome;
use Carbon\Carbon;
use App\Services\IncomeService;
use App\Services\OutcomeService;


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

        $request = new Request(['month' => true]);
        $incomes = $this->incomeService->getAll($request);
        $outcomes = $this->outcomeService->getAll($request);

        $incomesTotal = $incomes->sum('amount');
        $outcomesTotal = $outcomes->sum('amount');
        $balance = $incomesTotal - $outcomesTotal;
        
        return Inertia::render('Dashboard',[
            'incomes' => $incomes,
            'incomesTotal' => "$" . number_format($incomesTotal).'MXN',
            'outcomesTotal' => "$" . number_format($outcomesTotal).'MXN',
            'outcomes' => $outcomes,
            'balance' => "$" . number_format($balance).'MXN',
        ]);
    }
}
