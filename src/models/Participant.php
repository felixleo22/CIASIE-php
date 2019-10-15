<?php

namespace Smash\models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model {

    use SoftDeletes;
	protected $table = 'participantCombat';
	protected $primaryKey = 'id';
    protected $fillable = ['id' ,"combat_id", "entite_id", "pointVie", "nbAttaqueInflige", "nbAttaqueRecu", "degatInflige", "degatRecu", "gagner"];
    public $timestamps = true;
    
	public function combat(){
		return $this->belongsTo(Combat::class);
    }
    
    public function entite(){
		return $this->belongsTo(Entite::class);
	}
}
