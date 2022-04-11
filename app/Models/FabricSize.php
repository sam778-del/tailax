<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FabricSize extends Model
{
    protected $table = 'fabric_sizes';

    protected $fillable = [
        'size',
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
