<?php

namespace App\Models\Diskussion;
use App\Models\Diskussion\Kommentar;

class Umfrage extends Model
{
    protected $fillable = [
        'text', 'mehrfachauswahl',
    ];

    protected $casts = [
        'antworten' => 'array',
        'id' => 'integer',
        'text' => 'string',
        'mehrfachauswahl' => 'boolean',
    ];

    /**
     * Beziehung zum Kommentar, da zweite Variante
     */
    public function kommentar()
    {
        return $this->belongsTo(Kommentar::class);
    }
}