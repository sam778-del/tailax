<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TailorProduct extends Model
{
    protected $table = 'tailor_products';

    protected  $fillable = [
        'garment_name',
        'description',
        'gender',
        'stiching_charges',
        'category_id',
        'branch_id',
        'process_name',
        'measurement_name',
        'gallery',
        'fabric_consumption'
    ];
}
