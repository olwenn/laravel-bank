<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'total',
        'client_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users');
    }

}
