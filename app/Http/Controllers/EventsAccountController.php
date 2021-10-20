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
        
    public function manager( Request $request ){
        $current_user = JWTAuth::parseToken()->authenticate();
        
        if ( $request->input( 'type' ) === 'deposit' ) {

            return $this->deposit(
                $request->input( 'destination' ),
                $request->input( 'quantity' ),
                $current_user
            );
        } 
        elseif ($request->input( 'type' ) === 'withdraw' ) {

            return $this->withdraw (
                $request->input('destination'),
                $request->input('quantity'),
                $current_user
            );
        }
        elseif ($request->input( 'type' ) === 'paid' ) {

            return $this->payment (
                $request->input('destination'),
                $request->input('quantity'),
                $request->input('reason'),
                $request->input('id_loan'),
                $current_user
            );
        }
        elseif ($request->input( 'type' ) === 'show' ) {

            $account = BankAccount::findOrFail(
                $request->input('destination')
            );

            return response()->json(
                [
                    'origin' => [
                        'id' => $account->id,
                        'total' => $account->total
                ]
            ], 201);
        }
        elseif ($request->input( 'type' ) === 'showAll' ) {

            $accounts = BankAccount::where('id_client', $current_user->id )
                        ->get();
            return $accounts;
        }
        
    }

    private function deposit($destination, $quantity, $current_user){
        
        $account = BankAccount::findOrFail(
            [
                'id' => $destination,
                'client_id' => $current_user->id,
            ]
        );
        $account[0]->total += $quantity;
        $account[0]->save();

        return response()->json(
            [
                'destination' => [
                    'id' => $account[0]->id,
                    'total' => $account[0]->total
            ]
        ], 201);
    }

    private function withdraw ($origin, $quantity,$current_user){
        
        $account = BankAccount::findOrFail(
            [
                'id' => $origin,
                'client_id' => $current_user->id,
            ]
        );

        
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

    private function payment( $destination, $quantity, $reason, $id_loan,$current_user ){
        $account = BankAccount::findOrFail(
            [
                'id' => $destination,
                'client_id' => $current_user->id,
            ]
        );

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
        }

        
        $paid = new PaymentHistory();
        $paid->reason = $reason ;
        $paid->quantity_paid = $quantity;
        $paid->bankAcc_id = $destination;
        $paid->save();

        return $account;
    }
}
