<?php
namespace smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Smash\models\Entite;

class Combat extends Model
{
    use SoftDeletes;
    protected $table = 'combat';
    protected $fillable = ['id', 'termine'];
    public $timestamps = true;

    public function isEnd() : bool {
        return $this->pointViePersonnage <= 0 || $this->pointVieMonstre <= 0;
    }

    public function participants() {
        $this->hasMany('Smash\models\Participant');
    }

} 