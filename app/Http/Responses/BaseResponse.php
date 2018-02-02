<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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
     * @var null|Model $object
     */
    protected $object = null;

    /**
     * @var array $mapping
     */
    protected $mapping = [];

    /**
     * @param Model $object
     */
    public function setObject(Model $object)
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

        //Get all the eager loaded relations
        $relations = $this->object->getRelations();

        //Apply mapping
        foreach ($this->mapping as $key => $value) {
            //If there is no attribute and no loaded relation, skip this mapping
            if (!Schema::hasColumn($this->object->getTable(), $value) &&
            !isset($relations[$value])) {
                continue;
            }

            $output[$key] = $this->object->{$value};
        }

        return $output;
    }
}
