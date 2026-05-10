<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'projektname',
        'beschreibung',
        'bearbeitungsstatus',
        'mitglied',
        'ersteller_id',
    ];

    // Beziehung zum Nutzer (Ersteller)
    public function user()
    {
        return $this->belongsTo(User::class, 'ersteller_id');
    }
}