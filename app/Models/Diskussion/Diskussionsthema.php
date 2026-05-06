<?php

namespace App\Models\Diskussion;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Diskussionsthema extends Model
{
    protected $fillable = [
        'id', 'titel', 'erstelldatum', 'bearbeitungsdatum', 'ersteller',
    ];

    protected $attributes = [
        'titel' => 'neues Diskussionsthema',
        'erstelldatum' => now(),
        'bearbeitungsdatum' => now(),
    ];

    /**
     * Beziehung: Ersteller des Themas
     */
    public function ersteller()
    {
        return $this->belongsTo(Nutzer::class, 'nutzer_id');
    }
}