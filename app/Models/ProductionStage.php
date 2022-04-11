<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class ProductionStage extends Model
{
    /**
     * @var string
     */
    protected $table = 'production_stages';

    /**
     * @var string[]
     */
    protected  $fillable = [
        'process_name',
        'created_by'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
