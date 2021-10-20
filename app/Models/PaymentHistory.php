<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = [
        'reason',
        'quantity_paid',
        'banAcc_id',
    ];
    use HasFactory;
}
