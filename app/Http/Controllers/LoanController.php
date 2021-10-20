<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\BankAccount;

class LoanController extends Controller
{
    public function create( Request $request ){
        
        $account = BankAccount::findOrFail(
            $request->input('destination')
        );
        $loan = new Loans();
        $loan->debt = $request->input('quantity');
        $loan->bankAcc_id = $request->input('destination');
        $loan->save();

        $account->total += $request->input('quantity');
        $account->save();
    }
}
