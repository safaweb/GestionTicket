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

    public const OPEN = 1;
    public const ASSIGNED = 2;
    public const IN_PROGRESS = 3;
    public const ON_HOLD = 4;
    public const ESCALATED = 5;
    public const PENDING_CUSTOMER_RESPONSE = 6;
    public const RESOLVED = 7;
    public const CLOSED = 8;
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
