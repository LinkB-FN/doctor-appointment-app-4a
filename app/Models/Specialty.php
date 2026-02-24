<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the doctors associated with this specialty.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
