<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProblemCategory.
 * @property int $id
 * @property string $name
 * @property Collection|Ticket[] $tickets
 */
class ProblemCategory extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'problem_categories';

    protected $fillable = [
        'name',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
