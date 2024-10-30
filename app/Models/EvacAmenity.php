<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'evac_center_id', 
        'amenity_id', 
        'quantity', 
        'description',
    ];
}
