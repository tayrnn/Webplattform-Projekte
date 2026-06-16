<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projekt\Projekt;

class ProjektSeeder extends Seeder
{
    public function run(): void
    {
 Projekt::create([
    'projektname' => 'StudyBuddy',
    'beschreibung' => 'Plattform zum Finden von Lernpartnern für Studierende.',
    'bearbeitungsstatus' => 'offen',
    'mitglied' => 1,
    'ersteller_id' => 2,
]);

Projekt::create([
    'projektname' => 'CampusConnect',
    'beschreibung' => 'Digitale Plattform für Projektideen und Zusammenarbeit.',
    'bearbeitungsstatus' => 'in_bearbeitung',
    'mitglied' => 3,
    'ersteller_id' => 2,
]);

 Projekt::create([
    'projektname' => 'Smart Library',
    'beschreibung' => 'System zur intelligenten Verwaltung von Bibliotheksplätzen.',
    'bearbeitungsstatus' => 'offen',
    'mitglied' => 2,
    'ersteller_id' => 2,
]);
    }
}
