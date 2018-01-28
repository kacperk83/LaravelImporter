<?php

namespace App\Models;

use Illuminate\Contracts\Support\Responsable;
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
 *
 * @package App\Models
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'address', 'checked', 'description', 'interest', 'date_of_birth', 'email','account'];

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
