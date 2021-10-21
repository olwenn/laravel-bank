<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\BankAccount;
use App\Models\PaymentHistory;

class PaymentHistoryController extends Controller
{
    //Mostrar pagos
    public function show( Request $request ){

        $current_user = JWTAuth::parseToken()->authenticate();

        //Busqueda de la cuenta bancaria segun id_user
        $accounts = BankAccount::where( 'client_id' , $current_user->id )
                                ->get();
        $loans = "";

        foreach ($accounts as $value) {
            
            $loan = Loan::where( 'banckAcc_id' , $value->id )->get();
            $loans += $loan;
        }

        return response()->json( compact( 'loans' ) , 201 );
    }
}
