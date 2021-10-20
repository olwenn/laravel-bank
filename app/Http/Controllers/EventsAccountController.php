<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\PaymentHistory;
use App\Models\Loans;

class EventsAccountController extends Controller
{
        
    public function manager( Request $request ){
        if ( $request->input( 'type' ) === 'deposit' ) {

            return $this->deposit(
                $request->input( 'destination' ),
                $request->input( 'quantity' )
            );

        } 
        elseif ($request->input( 'type' ) === 'withdraw' ) {
            return $this->withdraw (
                $request->input('destination'),
                $request->input('quantity')
            );
        }
        elseif ($request->input( 'type' ) === 'paid' ) {
            return $this->payment (
                $request->input('destination'),
                $request->input('quantity'),
                $request->input('reason'),
                $request->input('id_loan')
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
        
    }

    private function deposit($destination, $quantity){

        $account = BankAccount::firstOrCreate(
            [
                'id' => $destination
            ]
        );

        $account->total += $quantity;
        $account->save();

        return response()->json(
            [
                'destination' => [
                    'id' => $account->id,
                    'total' => $account->total
            ]
        ], 201);
    }

    private function withdraw ($origin, $quantity){
        
        $account = BankAccount::findOrFail($origin);

        
        $account->total -= $quantity;
        $account->save();

        return response()->json(
            [
                'origin' => [
                    'id' => $account->id,
                    'total' => $account->total
            ]
        ], 201);

    }

    private function payment( $destination, $quantity, $reason, $id_loan ){
        $account = BankAccount::findOrFail( $destination );

        $account->total -= $quantity;
        $account->save();

        
        $paid = new PaymentHistory();
        $paid->reason = $reason ;
        $paid->quantity_paid = $quantity;
        $paid->bankAcc_id = $destination;
        $paid->save();
        if ($reason  === "loan") {
            //Consulta multiple
            $loan = Loans::where( 'bankAcc_id', $destination )
                            ->where( 'id', $id_loan )            
                            ->get();
            $loan[0]->debt -= $quantity;
            $loan[0]->total_paid += $quantity;
            $loan[0]->save();
        }
        return $account;
    }
}
