<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable =  ['id'];

    public function client(){
        return $this->belongsTo('App\Models\Clients');
    }

}
