<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'barangay_id', 
        'address', 
        'longitude', 
        'latitude', 
        'capacity', 
        'contact_person', 
        'contact_number', 
        'description',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function evacuees()
    {
        return $this->hasMany(Evacuee::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'evac_amenities');
    }

    public function needs()
    {
        return $this->hasMany(Need::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'evac_center_user');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

}
