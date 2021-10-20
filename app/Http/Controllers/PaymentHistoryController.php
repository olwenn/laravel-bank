<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\BankAccount;
use App\Models\PaymentHistory;

class PaymentHistoryController extends Controller
{
    public function return( Request $request ){

        $origin = $request->input('destination');
        $loans = Loans::where('bankAcc_id', $origin )->get();
        return $loans;
    }
}
