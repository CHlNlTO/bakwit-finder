<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 
        'urgency', 
        'evac_center_id',
    ];

    public function evacCenter()
    {
        return $this->belongsTo(EvacCenter::class);
    }
}
