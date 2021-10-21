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
        
    public function show( Request $request ){
        $current_user = JWTAuth::parseToken()->authenticate();
        
        $accounts = BankAccount::where('client_id', $current_user->id )
                        ->get();
        return $accounts;
    }
    
    public function deposit( Request $request ){
       
        $destination =  $request->input( 'destination' );
        $quantity =  $request->input( 'quantity' );
        $current_user = JWTAuth::parseToken()->authenticate();
        
        $account = BankAccount::where( 'id', $destination )
                            ->where( 'client_id', $current_user->id )            
                            ->get();

        $account[0]->total += $quantity;
        $account[0]->save();

        return response()->json(
            [
                'error' => false ,
                'destination' => [
                    'id' => $account[0]->id,
                    'total' => $account[0]->total
            ]
        ], 201);

    }

    public function withdraw( Request $request ){
        $origin =  $request->input( 'origin' );
        $quantity =  $request->input( 'quantity' );
        $current_user = JWTAuth::parseToken()->authenticate();
        
        $account = BankAccount::where( 'id', $origin )
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

    public function payment( Request $request ){

        $destination =  $request->input( 'destination' );
        $quantity =  $request->input( 'quantity' );
        $reason =  $request->input( 'reason' );
        $id_loan =  $request->input( 'id_loan' );
        $current_user = JWTAuth::parseToken()->authenticate();

        $account = BankAccount::where( 'id', $destination )
                            ->where( 'client_id', $current_user->id )            
                            ->get();

        $account[0]->total -= $quantity;
        $account[0]->save();

        
        if ($reason  === "loan") {
            //Consulta multiple
            $loan = Loans::where( 'bankAcc_id', $destination )
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

            $paid = new PaymentHistory();
            $paid->reason = $reason ;
            $paid->quantity_paid = $quantity;
            $paid->bankAcc_id = $destination;
            $paid->save();
        }

        

        return $account;
    }
}
