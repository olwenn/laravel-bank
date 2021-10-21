<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BankAccount;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class BankAccountController extends Controller
{
    
    public function createAccount( Request $request ){
        $current_user = JWTAuth::parseToken()->authenticate();
        
        $bank_account = new BankAccount();
        $bank_account->client_id = $current_user->id;
        $bank_account->save();
        
        return response()->json( compact( 'bank_account' ) , 201 );
    }

}
