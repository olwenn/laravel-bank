<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\BankAccount;

class ClientsController extends Controller
{
    public function create( Request $request ){
        
        $client = new Clients();
        $client->name = $request->input('name');
        $client->email = $request->input('email');
        $client->save();

        $bank_account = new BankAccount();
        $bank_account->client_id = $client->id;
        $bank_account->save();
    }
}
