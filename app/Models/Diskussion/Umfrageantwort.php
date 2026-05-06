<?php

namespace App\Models\Diskussion;

class Umfrageantwort extends Model 
{
    protected $fillable = [
        'nummer',
        'text',
        'anzahl',
        'antwortgeber'
    ];

    /**
     * Beziehung zur Umfrage
     */
    public function umfrage()
    {
        return $this->belongsTo(Umfrage::class);
    }

    public function loeschen()
    {
        $this->delete();
    }

}