<?php

namespace Smash\models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combat extends Model {

    use SoftDeletes;
	protected $table = 'combat';
	protected $primaryKey = 'id';
    protected $fillable = ['id' , "termine", "nbTours"];
    public $timestamps = true;

	public function participants(){
		return $this->hasMany(Participant::class);
    }
    
}
