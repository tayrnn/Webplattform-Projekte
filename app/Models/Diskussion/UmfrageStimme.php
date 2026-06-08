<?php

namespace App\Models\Diskussion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Diskussion\Umfrage;
use App\Models\Nutzer\Nutzer;

class UmfrageStimme extends Model
{
    protected $table = 'poll_votes';

    protected $fillable = [
        'poll_id',
        'user_id', 
        'poll_option_id'
    ];

    /**
     * Die Umfrage, für die diese Stimme abgegeben wurde.
     */
    public function umfrage(): BelongsTo
    {
        return $this->belongsTo(Umfrage::class, 'poll_id');
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