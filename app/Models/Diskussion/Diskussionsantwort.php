<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Nutzer\Nutzer;

class Diskussionsantwort extends Model
{
    protected $table = 'discussion_answers';

    protected $fillable = [
        'discussion_id', 
        'user_id', 
        'content',
        'parent_id' // NULL -> Hauptkommentar, sonst Antwort
    ];

    /**
     * Der Nutzer, der diese Antwort verfasst hat.
     */
    public function ersteller(): BelongsTo
    {
        return $this->belongsTo(Nutzer::class, 'user_id')
        ->withDefault([
            'name' => 'Unbekannter Nutzer', // Gelöschter Nutzer
        ]);
    }

    /**
     * Das übergeordnete Diskussionsthema.
     */
    public function diskussion(): BelongsTo
    {
        return $this->belongsTo(Diskussion::class, 'discussion_id');
    }

    /**
     * Alle Antworten, die auf diese Antwort antworten.
     */
    public function unterantworten(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('created_at', 'asc');
    }
}