<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacueeFamilyMember extends Model
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
        'evacuee_id',
    ];

    public function evacuee()
    {
        return $this->belongsTo(Evacuee::class);
    }
}
