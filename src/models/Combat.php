<?php
namespace smash\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Smash\models\Entite;

class Combat extends Model
{
    use SoftDeletes;
    protected $table = 'combat';
    protected $fillable = ['id', 'termine', 'nbTours'];
    public $timestamps = true;

    public function participants() {
        $this->hasMany(Participant::class);
    }

} 