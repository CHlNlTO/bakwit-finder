<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'evac_center_id',
    ];

    // A family belongs to one evacuation center
    public function evacCenter()
    {
        return $this->belongsTo(EvacCenter::class);
    }

    // A family has many evacuee members
    public function members()
    {
        return $this->hasMany(Evacuee::class);
    }

    // Helper method to get the head of the family (first member or null)
    public function head()
    {
        return $this->members()->first();
    }
}
