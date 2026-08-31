<?php

namespace App\Models\Projekt;

use App\Models\Nutzer\Nutzer;
use App\Models\Projekt\Bewertung;
use App\Models\Projekt\Kategorie;
use App\Models\Diskussion\Diskussion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// https://stitcher.io/blog/php-enums

class Projekt extends Model
{
    use HasFactory;


    protected $table = 'projects';

    protected $fillable = [
        'projektname',
        'beschreibung',
        'bearbeitungsstatus', // offen, geschlossen
        'bildpfad',
        'ersteller_id',
        'betreuer_id',
        'is_public',
    ];

    protected function casts(): array
    {
        return [

            'bearbeitungsstatus' => Bearbeitungsstatus::class,
        ];
    }

    /*
     * Beziehungen zu anderen Modellen
    */

    // ein Projekt gehört zu einem Ersteller (Nutzer)
    public function ersteller(): BelongsTo
    {
        return $this->belongsTo(Nutzer::class, 'ersteller_id');
    }

    // ein Projekt hat viele Diskussionen
    public function diskussionen(): HasMany
    {
        return $this->hasMany(Diskussion::class, 'project_id');
    }

    // ein Projekt hat viele Bewertungen
    public function bewertungen(): HasMany
    {
        return $this->hasMany(\App\Models\Projekt\Bewertung::class, 'projekt_id');
    }

    // ein Projekt gehört zu vielen Kategorien (n:m Beziehung)
    public function kategorien(): BelongsToMany
    {
        return $this->belongsToMany(Kategorie::class, 'projekt_kategorie', 'projekt_id', 'kategorie_id');
    }

    // ein Projekt wird ggf. von einem Lehrenden betreut
    public function betreuer(): BelongsTo
    {
        return $this->belongsTo(Nutzer::class, 'betreuer_id');
    }

    /*
     * Logik-Methoden
    */

    public function istOffen(): bool
    {
        return $this->bearbeitungsstatus === Bearbeitungsstatus::Offen;
    }
    // $offeneProjekte = Projekt::where('bearbeitungsstatus',
    // Bearbeitungsstatus::OFFEN)->get();

    public function bewerte(
        Nutzer $nutzer,
        int $sterne,
        string $kommentar = null
    ) {
        if ($sterne < 1 || $sterne > 5) {
            throw new \Exception("Bewertung muss zwischen 1 und 5 Sternen liegen.");
        }

        // Sucht nach nutzer_id, wenn gefunden -> Update, wenn nicht -> Create
        return $this->bewertungen()->updateOrCreate(
            ['nutzer_id' => $nutzer->id], // Suchkriterium
            ['bewertung' => $sterne, 'kommentar' => $kommentar] // Daten
        );
    }

    public function berechneDurchschnittsbewertung(): float
    {
        $durchschnitt = $this->bewertungen()->avg('bewertung');
        return $durchschnitt ? round((float)$durchschnitt, 2) : 0.0;
        // nutzt die avg() Methode von Eloquent
        // gibt 0 zurück, wenn es keine Bewertungen gibt
    }

    // Holt alle Projekte, sortiert nach dem höchsten Durchschnitt
    // $projekte = Projekt::withAvg('bewertungen', 'bewertung')
    // ->orderByDesc('bewertungen_avg_bewertung')
    // ->get();

}