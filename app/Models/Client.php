<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Vehicle;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'notes'
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}