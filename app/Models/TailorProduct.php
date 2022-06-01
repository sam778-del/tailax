<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;

/**
 *
 */
class TailorProduct extends Model
{
    /**
     * @var string
     */
    protected $table = 'tailor_products';

    /**
     * @var string[]
     */
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

    /**
     * @return mixed
     */
    public function branches()
    {
        return Branch::pluck('name', 'id');
    }
}
