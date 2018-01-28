<?php

namespace App\Http\Controllers\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

/**
 * Class BaseController
 *
 * @package App\Http\Controllers\V1
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class BaseController extends Controller
{
    /**
     * @var Request $request
     */
    protected $request;

    /**
     * BaseController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
