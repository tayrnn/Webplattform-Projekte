<?php

namespace App\Models\Projekt;

enum Bearbeitungsstatus: string
{
    case Offen = 'offen';
    case Abgeschlossen = 'abgeschlossen';
}