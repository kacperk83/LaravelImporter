<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Class UsersRepository
 *
 * @package App\Repositories
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class UsersRepository
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * UsersRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function get(int $id)
    {
        return $this->user->newQuery()
                    ->where('id', $id)
                    ->get()
                    ->first();
    }
}
