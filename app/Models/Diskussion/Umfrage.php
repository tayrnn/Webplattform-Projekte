<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Nutzer\Nutzer;
use App\Models\Projekt\Projekt;

class Umfrage extends Model
{
    protected $table = 'polls';

    protected $fillable = [
        'projekt_id',
        'user_id',
        'frage',
    ];

    /**
     * Das Projekt, zu dem diese Umfrage gehört.
     */
    public function projekt(): BelongsTo
    {
        return $this->belongsTo(Projekt::class, 'projekt_id');
    }

    /**
     * Der Nutzer, der diese Umfrage erstellt hat.
     */
    public function ersteller(): BelongsTo
    {
        return $this->belongsTo(Nutzer::class, 'user_id')
        ->withDefault([
            'name' => 'Unbekannter Nutzer', // Gelöschter Nutzer
        ]);
    }

    /**
     * Alle Optionen dieser Umfrage.
     */
    public function optionen(): HasMany
    {
        return $this->hasMany(UmfrageOption::class, 'poll_id');
    }

    /**
     * Alle Stimmen, die für diese Umfrage abgegeben wurden.
     */
    public function stimmen(): HasMany
    {
        return $this->hasMany(UmfrageStimme::class, 'poll_id');
    }

    /**
     * Prüft, ob ein bestimmter Nutzer bereits an dieser Umfrage teilgenommen hat.
     */
    public function hatNutzerAbgestimmt($nutzerId): bool
    {
        return $this->stimmen()->where('user_id', $nutzerId)->exists();
    }
}