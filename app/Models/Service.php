<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $fillable = [
        'vehicle_id',
        'description',
        'status',
        'price',
        'started_at',
        'finished_at'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
