<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Validation.
 * @property int $id
 * @property string $name
 * @property Collection|Ticket[] $tickets
 */
class Validation extends Model
{
    //use SoftDeletes;
    public const ACCEPTER = 1;
    public const REFUSER= 2;
    public const TERMINER = 3;
    public const RIEN = 4;
    public $timestamps = false;
    protected $table = 'validation';
    protected $fillable = [
        'name',
    ];

    /**Get all of the tickets for the Validation.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'validation_id');
    }
}
