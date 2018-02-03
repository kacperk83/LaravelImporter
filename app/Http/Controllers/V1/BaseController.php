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
     * @var array $queryParams
     */
    protected $queryParams;

    /**
     * BaseController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @deprecated
     *
     * @return array
     */
    public function getExpands()
    {
        return $this->request->query('expand', []);
    }

    //@todo: ophalen query parameters zoals: expand, limit en offset
    //@todo: + valideren + plaatsen in $queryParams
}
