<?php
namespace Smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use SoftDeletes;
    protected $table = 'compteAdmin';
    protected $primaryKey = 'id';
    protected $fillable = ['login', 'mdp', 'super'];
    public $timestamps = true;
} 