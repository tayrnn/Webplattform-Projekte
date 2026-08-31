<?php

namespace App\Models\Nutzer;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Database\Factories\UserFactory;
// use DateTime;
// use Illuminate\Database\Eloquent\Attributes\Fillable;
// use Illuminate\Database\Eloquent\Attributes\Hidden;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
use App\Models\Nutzer\Rollentyp;
use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nutzer extends Authenticatable
{

    // /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'benutzername',
        'passwort',
        'email',
        'rolle',
        'registrierungsDatum'
    ];

    protected $hidden = [
        'passwort',
        'remember_token'
    ];

    // guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'passwort' => 'hashed',
            'registrierungsDatum' => 'datetime',
            'rolle' => Rollentyp::class, // Wichtig für den Vergleich mit Enums
        ];
    }

    public function ersteller()
    {

        return $this->belongsTo(Nutzer::class, 'ersteller_id');
    }

    public function getAuthPassword()
    {
        return $this->passwort;
    }

    public function selbstLoeschen()
    {
        // === -> Wert und Datentyp müssen gleich sein
        if ($this->rolle === Rollentyp::ADMIN) {
            throw new Exception("Admin kann nicht gelöscht werden!");
        }
        return $this->delete();
    }

    public function nutzerLoeschen(Nutzer $nutzer): bool
    {
        if ($nutzer->rolle === Rollentyp::ADMIN) {
            throw new Exception("Admin kann nicht gelöscht werden!");
        }
        return $nutzer->delete();
    }

    public function isAdmin(): bool
    {
        return $this->rolle === Rollentyp::ADMIN;
    }
    // if ($this->isAdmin()) { ... }

}