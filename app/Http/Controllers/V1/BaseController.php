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
     * @var array $queryParams
     */
    protected $queryParams = [

        'expand' => [
            'rules' => 'array',
            'default' => []
        ],
        'limit' => [
            'rules' => 'int',
            'default' => 10
        ],
        'offset' => [
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
     * processQueryParams
     *
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
     * getCleanQueryParams
     */
    protected function getCleanQueryParams()
    {
        return $this->cleanQueryParams;
    }

    /**
     * @param string $queryParam
     * @param string $rules
     */
    protected function updateQueryParamRules(string $queryParam, string $rules)
    {
        $this->queryParams[$queryParam]['rules'] = $rules;
    }

    /**
     * @param array $params
     */
    protected function deleteQueryParams(array $params)
    {
        $this->queryParams = array_diff($params, $this->queryParams);
    }
}
