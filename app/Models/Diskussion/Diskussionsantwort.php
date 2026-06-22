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
        'parent_id' // NULL -> Hauptkommentar, sonst Antwort
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'edited_at' => 'datetime',
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

    public function darfBearbeiten($user): bool
    {
        return $user && $user->id === $this->user_id;
    }

    public function darfLoeschen($user): bool
    {
        // Admin darf alles, Ersteller darf seine eigenen, Diskussion-Ersteller/Projekt-Inhaber dürfen auch
        return $user->isAdmin() ||
            $user->id === $this->user_id ||
            $user->id === $this->diskussion->user_id ||
            $user->id === $this->diskussion->projekt->user_id;
    }
}