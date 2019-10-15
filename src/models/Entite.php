<?php
namespace smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entite extends Model {

    use SoftDeletes;
    protected $table = 'entite';
    protected $fillable = ['type','nom', 'prenom', 'taille' , 'poids', 'pointVie', 'pointAtt', 'pointDef', 'pointAgi', 'photo', 'combatGagne','combatPerdu','degatRecu','degaInflige'];
    public $timestamps = true;

    public function defaultPhoto() {
        return $this->type === 'monstre' ? 'default_monstre.png' : 'default_personnage.png';
    }

    public function getPourcentageWin() {
        if ($this->combatGagne === NULL && $this->combatPerdu === NULL) {
            return 0;
        }
        return round($this->combatGagne / ($this->combatGagne + $this->combatPerdu));
    }
} 