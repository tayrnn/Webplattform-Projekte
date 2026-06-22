<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Projekt\Projekt;

class Diskussion extends Model
{
    protected $table = 'discussions';

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
    ];

    /**
     * Der Nutzer, der dieses Thema gestartet hat.
     */
    public function ersteller(): BelongsTo
    {
        return $this->belongsTo(Nutzer::class, 'user_id')
            ->withDefault([
                'name' => 'Unbekannter Nutzer', // Gelöschter Nutzer
            ]);
    }

    /**
     * Das zugehörige Projekt.
     */
    public function projekt(): BelongsTo
    {
        return $this->belongsTo(Projekt::class, 'project_id');
    }

    /**
     * Alle Beiträge/Antworten chronologisch innerhalb dieses Diskussionsthemas.
     */
    public function antworten(): HasMany
    {
        return $this->hasMany(Diskussionsantwort::class, 'discussion_id')
            ->orderBy('created_at', 'asc');
    }
}