<?php

namespace App\Http\Controllers\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     * @var array $cleanQueryParams
     */
    protected $cleanQueryParams = [];

    /**
     * constants
     */
    const EXPAND = 'expand';
    const LIMIT = 'limit';
    const OFFSET = 'offset';

    /**
     * Here we define all the possible query parameters
     *
     * @var array $queryParams
     */
    protected $queryParams = [

        self::EXPAND => [
            'rules' => 'array',
            'default' => []
        ],
        self::LIMIT => [
            'rules' => 'int',
            'default' => 10
        ],
        self::OFFSET => [
            'rules' => 'int',
            'default' => 0
        ]
    ];

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
     * Here we collect the possible queryparams. If there is no value provided for a queryparam
     * we take the default one. Finally we validate every parameter.
     */
    protected function processQueryParams()
    {
        //collect input data
        $data = [];
        $rules = [];
        foreach (array_keys($this->queryParams) as $param) {
            $data[$param] =
                $this->request->query($param, $this->queryParams[$param]['default']);
            $rules[$param] = $this->queryParams[$param]['rules'];
        }

        Validator::make($data, $rules)->validate();

        $this->cleanQueryParams = $data;
    }

    /**
     * Return the query params after they have been processed
     */
    protected function getCleanQueryParams()
    {
        return $this->cleanQueryParams;
    }

    /**
     * Modify the rules for a given query param
     *
     * @param string $queryParam
     * @param string $rules
     */
    protected function updateQueryParamRules(string $queryParam, string $rules)
    {
        $this->queryParams[$queryParam]['rules'] = $rules;
    }

    /**
     * Delete one or more query params (because, for example, they are not allowed and we want
     * to ignore them)
     *
     * @param array $params
     */
    protected function deleteQueryParams(array $params)
    {
        $oldQueryParams = $this->queryParams;

        $keepThisQueryParams = array_diff(array_keys($this->queryParams), $params);

        $newQueryParams = [];
        foreach ($keepThisQueryParams as $param) {
            $newQueryParams[$param] = $oldQueryParams[$param];
        }
        $this->queryParams = $newQueryParams;
    }
}
