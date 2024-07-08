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
    public const ASSIGNE= 2;
    public const EN_COURS = 3;
    public const EN_ATTENTE = 4;
    public const ESCALADE = 5;
    public const EN_ATTENTE_DE_LA_REPONSE_DU_CLIENT = 6;
    public const RESOLU = 7;
    public const FERME = 8;
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
