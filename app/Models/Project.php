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
        'is_public',
    ];

    // Beziehung zum Nutzer (Ersteller)
    public function user()
    {
        return $this->belongsTo(User::class, 'ersteller_id');
    }

    // Beziehung zur Kategorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}