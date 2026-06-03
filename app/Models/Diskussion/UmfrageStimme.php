<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Nutzer\Nutzer;

class UmfrageStimme extends Model
{
    protected $table = 'poll_votes';

    protected $fillable = [
        'discussion_answer_id', 
        'user_id', 
        'poll_option_id'
    ];

    /**
     * Der Diskussionsbeitrag (die Umfrage), in dem abgestimmt wurde.
     */
    public function diskussionsantwort(): BelongsTo
    {
        return $this->belongsTo(Diskussionsantwort::class, 'discussion_answer_id');
    }

    /**
     * Der Nutzer, der seine Stimme abgegeben hat.
     */
    public function stimmgeber(): BelongsTo
    {
        return $this->belongsTo(Nutzer::class, 'user_id');
    }

    /**
     * Die gewählte Option.
     */
    public function gewaehlteOption(): BelongsTo
    {
        return $this->belongsTo(UmfrageOption::class, 'poll_option_id');
    }
}