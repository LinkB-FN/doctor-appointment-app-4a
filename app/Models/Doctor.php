<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'specialty_id',
        'medical_license',
        'biography',
    ];

    /**
     * Get the user that owns the doctor record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialty associated with the doctor.
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
