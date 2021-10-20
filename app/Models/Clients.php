<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable =  ['id'];

    public function banksaccount(){
        return $this->hasMany('App\Models\BankAccount');
    }
}
