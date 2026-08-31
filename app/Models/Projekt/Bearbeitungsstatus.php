<?php

namespace App\Models\Projekt;

enum Bearbeitungsstatus: string
{
    case Offen = 'offen';
    case InBearbeitung = 'in_bearbeitung';
    case Abgeschlossen = 'abgeschlossen';
    case BetreuerGesucht = 'betreuer_gesucht';
}
