<?php

namespace App\Models\Projekt;

use App\Models\Nutzer\Nutzer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bewertung extends Model {

    protected $fillable = [
        'projekt_id',
        'nutzer_id',
        'sterne',
        'kommentar',
    ];  

    /**
     * Beziehung: Wer hat die Bewertung abgegeben?
     */
    public function nutzer(): BelongsTo {
        return $this->belongsTo(Nutzer::class, 'nutzer_id');
    }

    /**
     * Beziehung: Welches Projekt wurde bewertet?
     */
    public function projekt(): BelongsTo {
        return $this->belongsTo(Projekt::class, 'projekt_id');  
    }

    /**
     * Validierung: Sterne müssen zwischen 1 und 5 liegen
     */
    public static function isValidSterne($sterne): bool {
        return is_int($sterne) && $sterne >= 1 && $sterne <= 5;
    }
    
}