<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserCreated;


/**
 * Class User.
 * @property int $id
 * @property null|int $societe_id
 * @property null|int $pays_id
 * @property string $name
 * @property string $email
 * @property null|string $password
 * @property null|string $two_factor_secret
 * @property null|string $two_factor_recovery_codes
 * @property null|Carbon $two_factor_confirmed_at
 * @property null|string $remember_token
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $pays
 * @property null|string $phone
 * @property null|int $user_level_id
 * @property bool $is_active
 * @property null|string $deleted_at
 * @property null|Societe $societe
 * @property null|Pays $pays
 * @property Collection|Commentaire[] $commentaires
 * @property Collection|Ticket[] $tickets
 */
class User extends Authenticatable implements FilamentUser
{
    use SoftDeletes, HasRoles, HasSuperAdmin, HasFactory, Notifiable;
    protected $table = 'users';

    protected $casts = [
        'societe_id'=>'int',
        'two_factor_confirmed_at' => 'datetime',
        'user_level_id' => 'int',
        'is_active' => 'bool',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'remember_token',
    ];

    protected $fillable = [
        'societe_id',
        'pays_id',
        'name',
        'email',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'remember_token',
        'pays',
        'phone',
        'user_level_id',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->password = Hash::make('12345678'); 
        });

        static::created(function ($user) {
            // Send notification here
            $password = '12345678'; // You can generate a random password here or use any logic you need
            $user->notify(new UserCreated($user->email, $password));
        });
    }

    /**Get the societe that owns the User.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo*/
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    /**Get the projet that owns the User.
    * public function projet()
    * {  return $this->belongsTo(Projet::class);}
    */
    
    /** Get the pays that owns the Ticket.
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo*/
    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    /** Get all of the commentaires for the User.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    /**Get all of the tickets for the User.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'owner_id');
    }

    /** Get all of the ticekt responsibility for the User.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function ticektResponsibility()
    {
        return $this->hasMany(Ticket::class, 'responsible_id');
    }

    /** Determine who has access.
     * Only active users can access the filament*/
    public function canAccessFilament(): bool
    {
        return auth()->user()->is_active;
    }

    /** Add scope to display users based on their role.
     * If the role is as an admin projet, then display the user based on their projet ID.*/
    public function scopeByRole($query)
    {
        if (auth()->user()->hasRole('Chef Projet')) {
        if (auth()->user()->hasRole('Chef Projet')) {
            //return $query->where('users.projet_id', auth()->user()->projet_id);
            return $query->where('users.societe_id', auth()->user()->societe_id);
        }
    }
    }

    /**Get all of the socialiteUsers for the User
     * @return \Illuminate\Database\Eloquent\Relations\HasMany*/
    public function socialiteUsers()
    {
        return $this->hasMany(SocialiteUser::class);
    }
}
