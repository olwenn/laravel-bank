<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BankAccount;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class BankAccountController extends Controller
{
    
    public function create( Request $request ){
        $current_user = JWTAuth::parseToken()->authenticate();
        
        $bank_account = new BankAccount();
        return $bank_account;
        $bank_account->client_id = $current_user->id;
        $bank_account->save();
    }

}
