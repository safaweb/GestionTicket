<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Qualification.
 *
 * @property int $id
 * @property string $name
 */
class Qualification extends Model
{
    protected $table = 'qualification';

    protected $fillable = [
        'name',
    ];

}
