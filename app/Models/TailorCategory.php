<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TailorCategory extends Model
{
    /**
     * @var string
     */
    protected $table = 'tailor_categories';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
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
