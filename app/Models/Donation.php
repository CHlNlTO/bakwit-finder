<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evac_center_id', 'donation_type', 'quantity', 'donator', 'beneficiary',
    ];

    public function evacCenter()
    {
        return $this->belongsTo(EvacCenter::class);
    }
}
