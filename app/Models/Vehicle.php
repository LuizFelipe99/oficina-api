<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class Vehicle extends Model
{
    protected $fillable = [
        'client_id',
        'plate',
        'brand',
        'model',
        'year',
        'color'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
