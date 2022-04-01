<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'branch_id',
        'created_by',
        'code',
        'name',
        'amount',
        'image',
        'description',
        'status'
    ];

    /**
     * Get the user associated with the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * Get the branch associated with the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
}
