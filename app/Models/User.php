<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class User
 *
 * @property int id
 * @property string name
 * @property string address
 * @property boolean checked
 * @property string description
 * @property string interest
 * @property Carbon date_of_birth
 * @property string email
 * @property string account
 * @property string imported_from
 * @property string linenumber
 *
 * @package App\Models
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class User extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_of_birth'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'address', 'checked', 'description', 'interest', 'date_of_birth', 'email','account',
                           'imported_from', 'linenumber'];

    /**
     * creditcard
     * Een user kan meerdere creditcards hebben
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditcards()
    {
        return $this->hasMany(Creditcard::class);
    }
}