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
} 