<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\PaymentHistory;
use App\Models\Loans;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class EventsAccountController extends Controller
{
        
    //Metodo de mostrar cuentas
    public function show( Request $request ){

        $current_user = JWTAuth::parseToken()->authenticate();

        //Busqueda de la cuenta bancaria segun id_user
        $accounts = BankAccount::where( 'client_id' , $current_user->id )
                                ->get();

        return response()->json( compact( 'accounts' ) , 201 );
    }
    
    //Metodo de deposito
    public function deposit( Request $request ){
       
        $destination =  $request->input( 'account_id' );
        $quantity =  $request->input( 'quantity' );
        $current_user = JWTAuth::parseToken()->authenticate();
        
        //Busqueda de la cuenta bancaria segun id_cuenta / id_user
        $account = BankAccount::where( 'id' , $destination )
                                ->where( 'client_id' , $current_user->id )            
                                ->get();

        $account[0]->total += $quantity;
        $account[0]->save();

        return response()->json(
            [
                'error' => false ,
                'Account' => [
                    'id' => $account[0]->id,
                    'total' => $account[0]->total
                ]
            ], 201);
            

    }

    //Metodo de retiro
    public function withdraw( Request $request ){

        $origin =  $request->input( 'account_id' );
        $quantity =  $request->input( 'quantity' );
        $current_user = JWTAuth::parseToken()->authenticate();
        
        //Busqueda de la cuenta bancaria segun id_cuenta / id_user
        $account = BankAccount::where( 'id' , $origin )
                            ->where( 'client_id', $current_user->id )            
                            ->get();

        $account[0]->total -= $quantity;
        $account[0]->save();

        return response()->json(
            [
                'origin' => [
                    'id' => $account[0]->id,
                    'total' => $account[0]->total
                ]
            ], 201);

    }

    //Metodo de pago casual o prestamo
    public function payment( Request $request ){

        $destination =  $request->input( 'account_id' );
        $quantity =  $request->input( 'quantity' );
        $reason =  $request->input( 'reason' );
        $id_loan =  $request->input( 'id_loan' );
        $current_user = JWTAuth::parseToken()->authenticate();

        //Busqueda de la cuenta bancaria segun id_cuenta / id_user
        $accounts = BankAccount::where( 'id' , $destination )
                                ->where( 'client_id', $current_user->id )            
                                ->get();
        
        

        //Comprobar si se paga un prestamo
        if ( $reason  === "loan" ) {
            $account = $accounts[0];
            $account->total -= $quantity;
            $account->save();
            //Busqueda del prestao segun id_cuenta / id_loan
            $loan = Loans::where( 'bankAcc_id' , $destination )
                            ->where( 'id', $id_loan )            
                            ->get();

            $loan[0]->debt -= $quantity;
            $loan[0]->total_paid += $quantity;
            $loan[0]->save();

            $paid = new PaymentHistory();
            $paid->reason = $reason ;
            $paid->quantity_paid = $quantity;
            $paid->bankAcc_id = $destination;
            $paid->save();
        }else{
            $account = $accounts[0];
            $account->total -= $quantity;
            $account->save();
            $paid = new PaymentHistory();
            $paid->reason = $reason ;
            $paid->quantity_paid = $quantity;
            $paid->bankAcc_id = $destination;
            $paid->save();
        }

        

        return response()->json( compact( 'account' ) , 201 );
    }
}
