<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use App\Models\Diskussion\Umfrage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class UmfrageOption extends Model

{
    protected $table = 'poll_options';

    protected $fillable = [
        'discussion_answer_id',
        'option_text'
        #, 'mehrfachauswahl' // Falls Sie diese Funktionalität hinzufügen möchten
    ];

    /**
     * Die Umfrage, zu der diese Option gehört.
     */
    public function umfrage(): BelongsTo
    {
        return $this->belongsTo(Umfrage::class, 'poll_id');
    }

    /**
     * Die konkreten Stimmen, die für diese Option abgegeben wurden.
     */
    public function stimmen(): HasMany
    {
        return $this->hasMany(UmfrageStimme::class, 'poll_option_id');
    }
}