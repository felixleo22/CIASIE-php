<?php
namespace smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Smash\models\Entite;

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

    public function isEnd() : bool {
        return $this->pointViePersonnage <= 0 || $this->pointVieMonstre <= 0;
    }

    public function vainqueur() {
        if($this->pointViePersonnage <= 0) return $this->monstre();
        if($this->pointVieMonstre <= 0) return $this->personnage();
        return null;
    }

} 