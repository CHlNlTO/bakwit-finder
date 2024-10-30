<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'city_id', 
        'contact_person', 
        'phone', 
        'email',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function evacCenters()
    {
        return $this->hasMany(EvacCenter::class);
    }

    public function evacuees()
    {
        return $this->hasMany(Evacuee::class);
    }
}
