<?php

namespace Smash\models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entite extends Model {

    use SoftDeletes;
	protected $table = 'entite';
	protected $primaryKey = 'id';
    protected $fillable = ['id' , "nom", "prenom", "type", "taille", "poids", "pointAtt", "pointDef", "pointAgi", "pointVie", "photo", "combatGagne", "combatPerdu", "totalDegatInflige" , "totalDegatRecu"];
    public $timestamps = true;

	public function participants(){
		return $this->hasMany(Participant::class);
    }
    
    public function defaultPhoto() {
        return $this->type === 'monstre' ? 'default_monstre.png' : 'default_personnage.png';
    }

    public function getPourcentageWin() {
        if ($this->combatGagne === NULL && $this->combatPerdu === NULL) {
            return 0;
        }
        return round($this->combatGagne / ($this->combatGagne + $this->combatPerdu),2) * 100 ;
    }
} 