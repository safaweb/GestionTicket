<?php
/*** Created by Reliese Model.*/
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Commentaire.
 * @property int $id
 * @property int $tiket_id
 * @property int $user_id
 * @property string $commentaire
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $deleted_at
 * @property User $user
 * @property Ticket $ticket */
class Commentaire extends Model
{
    use SoftDeletes;
    protected $table = 'commentaires';

    protected $casts = [
        'tiket_id' => 'int',
        'user_id' => 'int',
    ];

    protected $fillable = [
        'tiket_id',
        'user_id',
        'commentaire',
        'attachments',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'tiket_id');
    }
}
