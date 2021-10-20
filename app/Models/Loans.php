<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loans extends Model
{
    protected $fillable = [
    'debt',
    'total_paid',
    'banAcc_id',
    ];
    use HasFactory;
}
