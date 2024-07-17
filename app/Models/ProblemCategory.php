<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProblemCategory.
 *
 * @property int $id
 * @property string $name
 * @property Collection|Ticket[] $tickets
 */
//@property Projet $projet
// @property int $projet_id
class ProblemCategory extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $table = 'problem_categories';

    //protected $casts = [
      //  'projet_id' => 'int',
    //];

    protected $fillable = [
        //'projet_id',
        'name',
    ];
/*
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
*/
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
