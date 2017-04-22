<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class Creditcard
 *
 * @property int id
 * @property string $type
 * @property string number
 * @property string name
 * @property Carbon expiration_date
 * @property string imported_from
 * @property string linenumber
 *
 * @package App\Models
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class Creditcard extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['expiration_date'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'number', 'name', 'expiration_date','imported_from', 'linenumber'];

    /**
     * user
     * Een creditcard hoort bij een gebruiker
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
