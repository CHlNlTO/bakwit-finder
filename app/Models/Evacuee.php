<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evacuee extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
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
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // An evacuee belongs to one family
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    // Helper methods to get evacuation center and barangay through family relationship
    // public function barangay()
    // {
    //     return $this->family->barangay();
    // }

    public function evacCenter()
    {
        return $this->family->evacCenter();
    }

    // Helper method to get full name
    public function getFullNameAttribute()
    {
        return trim("{$this->last_name}, {$this->first_name} {$this->middle_name}");
    }
}
