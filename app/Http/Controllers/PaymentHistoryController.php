<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\BankAccount;
use App\Models\PaymentHistory;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class PaymentHistoryController extends Controller{
    
    //Mostrar pagos
   public function showPaymentHistory( Request $request ){

    $current_user = JWTAuth::parseToken()->authenticate();

    //Busqueda de la cuenta bancaria segun id_user
    $accounts = BankAccount::where( 'client_id' , $current_user->id )
                            ->get();
    $paids = [];

    foreach ($accounts as $value) {
        
        $paid = PaymentHistory::where( 'bankAcc_id' , $value->id )->get();
        
        //Merge de todos los Json encontrados en las busquedas 
        $paids = array_merge( $paids , json_decode( $paid , true ) );
    }

    return response()->json( compact( 'paids' ) , 201 );
}
}
