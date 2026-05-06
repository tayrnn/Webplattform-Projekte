<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Kommentar extends Model
{
    protected $fillable = [
        'text', 'istUmfrage',
    ];

    protected $attributes = [
        'text' => 'neuer Kommentar',
        'erstelldatum' => now(),
        'istUmfrage' => false,
    ];

    protected $casts = [
        'istUmfrage' => 'boolean',
        'erstelldatum' => 'datetime',
        'bearbeitunsdatum' => 'datetime',
    ];

    /**
     * Beziehung zum Nutzer, der den Kommentar erstellt hat
     */
    public function ersteller()
    {
        return $this->belongsTo(Nutzer::class);
    }

    /**
     * Beziehung zum Diskussionsthema, zu dem der Kommentar gehört
     */
    public function diskussionsthema()
    {
        return $this->belongsTo(Diskussionsthema::class);
    }

}