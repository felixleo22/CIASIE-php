<?php
namespace smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combat extends Model
{
    use SoftDeletes;
    protected $table = 'combat';
    protected $fillable = ['id', 'idPersonnage', 'pointViePersonnage', 'idMonstre', 'pointVieMonstre'];
    public $timestamps = true;

    public function monstre() {
        return Entite::find($this->idMonstre);
    }

    public function personnage() {
        return Entite::find($this->idPersonnage);
    }

} 