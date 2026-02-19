<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echange extends Model
{
    use HasFactory;

    protected $fillable = [
        'interlocuteur_id',
        'type',
        'contenu',
        'date_echange',
    ];

    /**
     * Relation avec l’interlocuteur concerné.
     */
    public function interlocuteur()
    {
        return $this->belongsTo(Interlocuteur::class);
    }
}
