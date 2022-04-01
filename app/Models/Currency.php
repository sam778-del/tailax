<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        "currecny_name",
        "currency_symbol",
        "is_default",
        "amount"
    ];
}
