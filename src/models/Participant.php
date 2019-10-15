<?php
namespace smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model {
    use SoftDeletes;
    protected $table = 'participantCombat';
    protected $fillable = ['pointVie', 'nbAttaqueInflige', 'nbAttaqueRecu', 'degatInflige', 'degatRecu', 'gagner'];
    public $timestamps = true;

    public function combat() {
        $this->belongsTo('Smash\models\Combat', 'idCombat');
    }

    public function entite() {
        $this->belongsTo('Entite', 'idEntite');
    }
}