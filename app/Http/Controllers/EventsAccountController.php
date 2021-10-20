<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;

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
        elseif ($request->input( 'type' ) === 'show' ) {
            $account = BankAccount::findOrFail(
                $request->input('destination')
            );

            return response()->json(
                [
                    'origin' => [
                        'id' => $account->id,
                        'balance' => $account->balance
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

        $account->balance += $quantity;
        $account->save();

        return response()->json(
            [
                'destination' => [
                    'id' => $account->id,
                    'balance' => $account->balance
            ]
        ], 201);
    }

    private function withdraw ($origin, $quantity){
        
        $account = BankAccount::findOrFail($origin);

        
        $account->balance -= $quantity;
        $account->save();

        return response()->json(
            [
                'origin' => [
                    'id' => $account->id,
                    'balance' => $account->balance
            ]
        ], 201);

    }
}
