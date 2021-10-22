<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\BankAccount;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoanController extends Controller
{
    //Crear prestamo
    public function create( Request $request ){
        
        $account = BankAccount::findOrFail(
            $request->input( 'account_id' )
        );

        $loan = new Loans();
        $loan->debt = $request->input( 'quantity' );
        $loan->bankAcc_id = $request->input( 'account_id' );
        $loan->save();

        $account->total += $request->input( 'quantity' );
        $account->save();

        return response()->json( compact( 'account' , 'loan' ) , 201 );
    }
    
    //Mostrar prestamos
    public function showLoanHistory( Request $request ){

        $current_user = JWTAuth::parseToken()->authenticate();

        //Busqueda de la cuenta bancaria segun id_user
        $accounts = BankAccount::where( 'client_id' , $current_user->id )
                                ->get();
        $loans = [];

        foreach ($accounts as $value) {
            
            $loan = Loans::where( 'bankAcc_id' , $value->id )->get();
            $loans = array_merge($loans,json_decode($loan,true));
        }

        return response()->json( compact( 'loans' ) , 201 );
    }

    
}
