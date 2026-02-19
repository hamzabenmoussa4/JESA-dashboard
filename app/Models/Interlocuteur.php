<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interlocuteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'notes',
        'user_id',
    ];

    /**
     * L'utilisateur (propriétaire) de l'interlocuteur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Les échanges liés à cet interlocuteur.
     */
    public function echanges()
    {
        return $this->hasMany(Echange::class);
    }
}
