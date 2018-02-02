<?php

namespace App\Http\Controllers\V1\Users;

use App\Http\Controllers\V1\BaseController;
use Illuminate\Http\Request;
use App\Http\Responses\UserResponse;
use App\Repositories\UsersRepository;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers\V1\Users
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class UsersController extends BaseController
{
    /**
     * @var UsersRepository $repo
     */
    private $repo;

    /**
     * @var UserResponse $userResponse
     */
    private $userResponse;

    /**
     * UsersController constructor.
     *
     * @param Request         $request
     * @param UsersRepository $repo
     * @param UserResponse    $response
     */
    public function __construct(
        Request $request,
        UsersRepository $repo,
        UserResponse $response
    ) {
        $this->repo = $repo;
        $this->userResponse = $response;

        parent::__construct($request);
    }

    /**
     * @param int $id
     *
     * @return UserResponse
     */
    public function show(int $id)
    {
        //validate data
        $this->request->validate([
            'expand' => 'sometimes|array|in:creditcards'
        ]);

        //Get the data
        $user = $this->repo->get($id, $this->getExpands());

        //Make the retrieved object available to the response
        $this->userResponse->setObject($user);

        //Return the response
        return $this->userResponse;
    }
}
