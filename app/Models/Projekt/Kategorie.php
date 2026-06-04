<?php

namespace App\Models\Projekt;

use Illuminate\Database\Eloquent\Model;
use Override;

class Kategorie extends Model
{
    protected $fillable = [
        'name',
    ];

    public function __construct()
    {
        $this->name = 'neue Kategorie';
    }

    public function loeschen()
    {
        // Logik zum Löschen der Kategorie
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    protected $table = 'categories';
}