<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Pays.
 * @property int $id
 * @property string $name
 * @property Collection|Ticket[] $tickets
 * @property Collection|User[] $Users
 */
class Pays extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'pays';

    protected $fillable = [
        'name',
    ];

    /** Get all of the tickets for the Pays
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }


    /** Get all of the users for the Pays
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
