<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StatutDuTicket.
 *
 * @property int $id
 * @property string $name
 * @property Collection|Ticket[] $tickets
 */
class StatutDuTicket extends Model
{
    use SoftDeletes;

    public const OUVERT = 1;
    public const EN_COURS = 2;
    public const RESOLU = 3;
    public const NONRESOLU = 4;
    public $timestamps = false;
    protected $table = 'statuts_des_tickets';

    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the tickets for the StatutDuTicket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'statuts_des_tickets_id');
    }
}
