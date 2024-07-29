<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Projet.
 * @property int $id
 * @property string $name
 * @property null|int $pays_id
 * @property Pays $pays
 * @property null|int $societe_id
 * @property Societe $societe
 * @property Collection|ProblemCategory[] $problem_categories
 * @property Collection|Ticket[] $tickets
 * @property Collection|User[] $Users
 */
class Projet extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'projets';

    protected $casts = [
        'pays_id' => 'int',
        'societe_id'=>'int',
        'user_id'=>'int',
    ];

    protected $fillable = [
        'name',
        'pays_id',
        'pays',
        'societe_id',
        'societe',
        'user_id',
        'user',
    ];

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    /** Get the pays that owns the Ticket.
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo*/
    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    /**Get all of the tickets for the Projet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**Get all of the users for the Projet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function users()
    {
    return $this->belongsToMany(User::class, 'projet_user', 'projet_id', 'user_id');
    }

}
