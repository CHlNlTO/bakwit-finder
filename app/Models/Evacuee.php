<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evacuee extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name', 
        'first_name', 
        'middle_name', 
        'gender', 
        'birth_date', 
        'age', 
        'religion', 
        'nationality', 
        'address', 
        'contact_number', 
        'email', 
        'sector', 
        'barangay_id', 
        'evac_center_id',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function evacCenter()
    {
        return $this->belongsTo(EvacCenter::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(EvacueeFamilyMember::class);
    }
}
