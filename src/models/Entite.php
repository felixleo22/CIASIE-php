<?php
namespace smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entite extends Model {

    use SoftDeletes;
    protected $table = 'Entite';
    protected $fillable = ['type','nom', 'prenom', 'taille' , 'pointVie', 'pointAtt', 'pointDef', 'pointAgi', 'photo'];
    public $timestamps = true;

} 