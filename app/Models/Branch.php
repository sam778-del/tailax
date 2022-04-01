<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        "name",
        "created_by"
    ];

    /**
     * Get the user associated with the Branch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }


    /**
     * Get all of the checkUserBranch for the Branch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checkUserBranch()
    {
        return $this->hasMany(User::class, 'branch_id');
    }
}
