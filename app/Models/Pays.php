<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Pays.
 * @property int $id
 * @property string $name
 * @property Collection|Ticket[] $tickets
 * @property Collection|User[] $Users
 */
class Pays extends Model
{
   // use SoftDeletes;
    public $timestamps = false;
    protected $table = 'pays';

    protected $fillable = [
        'name',
    ];

    public function getShortcutAttribute()
    {
        return substr($this->name, 0, 4); // or any logic you want to create the shortcut
    }

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
