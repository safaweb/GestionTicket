<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Societe.
 * @property int $id
 * @property string $name
 * @property Collection|Projet[] $projet
 * @property Collection|User[] $Users
 */
class Societe extends Model
{
   // use SoftDeletes;
    public $timestamps = false;
    protected $table = 'societes';

    protected $fillable = [
        'name',
    ];

    /**Get all of the  for the Societe
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function projet()
    {
        return $this->hasMany(Projet::class);
    }

    /**Get all of the users for the Societe
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany*/
    public function users()
    {
        return $this->belongsToMany(User::class, 'societe_user', 'societe_id', 'user_id');
    }
}
