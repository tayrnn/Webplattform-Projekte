<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Diskussionsantwort extends Model
{
    protected $table = 'discussion_answers';

    protected $fillable = [
        'discussion_id',
        'user_id',
        'content',
        'ist_umfrage'
    ];

    protected $casts = [
        'ist_umfrage' => 'boolean',
    ];

    /**
     * Der Nutzer, der diesen Beitrag verfasst hat.
     */
    public function ersteller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Das übergeordnete Diskussionsthema.
     */
    public function diskussion(): BelongsTo
    {
        return $this->belongsTo(Diskussion::class, 'discussion_id');
    }

    /**
     * Die Antwortoptionen, falls dieser Beitrag eine Umfrage ist.
     */
    public function umfrageOptionen(): HasMany
    {
        return $this->hasMany(UmfrageOption::class, 'discussion_answer_id');
    }

    /**
     * Alle abgegebenen Stimmen für diese spezifische Umfrage.
     */
    public function stimmen(): HasMany
    {
        return $this->hasMany(UmfrageStimme::class, 'discussion_answer_id');
    }

    /**
     * Hilfsmethode: Prüfen, ob ein bestimmter Nutzer bereits abgestimmt hat.
     */
    public function hatNutzerAbgestimmt($nutzerId): bool
    {
        return $this->stimmen()->where('user_id', $nutzerId)->exists();
    }
}
