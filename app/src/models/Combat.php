<?php

namespace Smash\models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combat extends Model {

    use SoftDeletes;
	protected $table = 'combat';
	protected $primaryKey = 'id';
    protected $fillable = ['id' , "termine", "nbTours", "prochainAttaquant", "prochainVictime"];
    public $timestamps = true;

	public function participants(){
		return $this->hasMany(Participant::class);
    }
    
    public function vainqueurs(){
        return $this->participants()->where('gagner','=',1);
    }

    public function perdants(){
        return $this->participants()->where('gagner','=',0);
    }
}
