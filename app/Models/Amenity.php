<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function evacCenters()
    {
        return $this->belongsToMany(EvacCenter::class, 'evac_amenities');
    }
}
