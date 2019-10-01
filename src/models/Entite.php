<?php
namespace smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entite extends Model {

    use SoftDeletes;
    protected $table = 'entite';
    protected $fillable = ['type','nom', 'prenom', 'taille' , 'poids', 'pointVie', 'pointAtt', 'pointDef', 'pointAgi', 'photo'];
    public $timestamps = true;

} 