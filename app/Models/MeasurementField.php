<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class MeasurementField extends Model
{
    /**
     * @var string
     */
    protected $table = 'measurement_fields';

    /**
     * @var string[]
     */
    protected $fillable = [
        'measurement_name',
        'value_type',
        'options'
    ];

    /**
     * @return string[]
     */
    public static function valueType()
    {
        return [
            'Single Line' => 'Single Line',
            'Dropdown Values' => 'Dropdown Values'
        ];
    }

    public static function MeasurementType()
    {
        return array(
            'J Length' => 'J Length',
            'J Shoulder' => 'J Shoulder',
            'J Sleeves'  => 'J Sleeves',
            'J Neck'     => 'J Neck',
            'J Chest'    => 'J Chest',
            'J Waist'    => 'J Waist',
            'J Hips'     => 'J Hips',
            'J Cross Back' => 'J Cross Back',
            'T Waist'    => 'T Waist',
            'T Hips'    => 'T Hips',
            'T Length'   => 'T Length',
            'T Crotch'  =>  'T Crotch',
            'T Thighs'   =>  'T Thighs',
            'T Knee'     =>  'T Knee',
            'T Bottom'   =>  'T Bottom',
            'T Inside'   =>  'T Inside',
            'T U'        =>   'T U',
            'S Length'  =>     'S Length',
            'S Shoulder' =>   'S Shoulder',
            'S Sleeves'  =>     'S Sleeves',
            'S Neck'     =>     'S Neck',
            'S Chest'    =>     'S Chest',
            'S Waist'    =>     'S Wasit',
            'S Hips'     =>      'S Hips',
            'S Cuffs'    =>      'S Cuffs',
        );
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
