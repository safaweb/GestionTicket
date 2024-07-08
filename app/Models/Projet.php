<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Projet.
 *
 * @property int $id
 * @property string $name
 * @property Collection|ProblemCategory[] $problem_categories
 * @property Collection|Ticket[] $tickets
 * @property Collection|User[] $Users
 */
class Projet extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $table = 'projets';

    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the problemCategories for the Projet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function problemCategories()
    {
        return $this->hasMany(ProblemCategory::class);
    }

    /**
     * Get all of the tickets for the Projet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all of the users for the Projet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
