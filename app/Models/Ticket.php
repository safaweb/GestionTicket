<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\StatutDuBilletModifie;

/**
 * Class Ticket.
 *
 * @property int $id
 * @property int $priority_id
 * @property int $projet_id
 *  @property int $pays_id
 * @property int $owner_id
 * @property int $problem_category_id
 * @property string $title
 * @property string $description
 * @property int $statuts_des_tickets_id
 * @property null|int $responsible_id
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Carbon $approved_at
 * @property null|Carbon $solved_at
 * @property null|string $deleted_at
 * @property Priority $priority
 * @property Projet $projet
 * @property Pays $pays
 * @property null|User $user
 * @property ProblemCategory $problem_category
 * @property StatutDuTicket $statut_du_ticket
 * @property Collection|Commentaire[] $commentaires
 */
class Ticket extends Model
{
    use SoftDeletes;
    protected $table = 'tickets';

    protected $casts = [
        'priority_id' => 'int',
        'projet_id' => 'int',
        'pays_id' => 'int',
        'owner_id' => 'int',
        'qualification_id' => 'int',
        'problem_category_id' => 'int',
        'statuts_des_tickets_id' => 'int',
        'responsible_id' => 'int',
        'approved_at' => 'datetime',
        'solved_at' => 'datetime',
        'accepted' => 'boolean',
    ];

    protected $fillable = [
        'priority_id',
        'projet_id',
        'pays_id' ,
        'owner_id',
        'qualification_id',
        'problem_category_id',
        'title',
        'description',
        'statuts_des_tickets_id',
        'responsible_id',
        'approved_at',
        'solved_at',
        'accepted',
    ];

    /**
     * Get the priority that owns the Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    /**
     * Get the projet that owns the Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
     /**
      * Get the pays that owns the Ticket.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function pays()
     {
         return $this->belongsTo(Pays::class);
     }
 

    /**
     * Get the owner that owns the Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the responsible that owns the Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    /**
     * Get the problemCategory that owns the Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problemCategory()
    {
        return $this->belongsTo(ProblemCategory::class);
    }
     /**
     * Get the user that owns the Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the statutDuTicket that owns the Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statutDuTicket()
    {
        return $this->belongsTo(StatutDuTicket::class, 'statuts_des_tickets_id');
    }

    /**
     * Get all of the commentaires for the Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'tiket_id');
    }
    
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($ticket) {
            if ($ticket->isDirty('statuts_des_tickets_id') && in_array($ticket->statuts_des_tickets_id, [StatutDuTicket::EN_COURS, StatutDuTicket::FERME])) {
                \Log::info('Status changed for ticket ID: ' . $ticket->id);
                if ($ticket->owner) {
                    \Log::info('Sending notification to user ID: ' . $ticket->owner->id);
                    $ticket->owner->notify(new StatutDuBilletModifie($ticket, $ticket->StatutDuTicket->name));
                } else {
                    \Log::warning('No user associated with ticket ID: ' . $ticket->id);
                }
            }
        });
    
    }
}
