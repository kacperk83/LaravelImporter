<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

/**
 * Class BaseResponse
 *
 * @package App\Http\Responses
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class BaseResponse implements Responsable
{
    /**
     * @var null $object
     */
    protected $object = null;

    /**
     * @var array $mapping
     */
    protected $mapping = [];

    /**
     * @param $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        $data = $this->map();
        return response()->json($data);
    }

    /**
     * @return array
     */
    private function map()
    {
        $output = [];

        foreach ($this->mapping as $key => $value) {
            $output[$key] = $this->object->{$value};
        }

        return $output;
    }
}
