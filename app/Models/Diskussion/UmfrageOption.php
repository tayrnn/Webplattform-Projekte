<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use App\Models\Diskussion\Kommentar;
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

    protected $casts = [
        #'mehrfachauswahl' => 'boolean',
    ];

    /**
     * Der Diskussionsbeitrag, zu dem diese Option gehört.
     */
    public function diskussionsantwort(): BelongsTo
    {
        return $this->belongsTo(Diskussionsantwort::class, 'discussion_answer_id');
    }

    /**
     * Die konkreten Stimmen, die exakt für diese Option abgegeben wurden.
     */
    public function stimmen(): HasMany
    {
        return $this->hasMany(UmfrageStimme::class, 'poll_option_id');
    }
}