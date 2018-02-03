<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class UsersRepository
 *
 * @package App\Repositories
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class UsersRepository extends BaseRepository
{

    /**
     * UsersRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
