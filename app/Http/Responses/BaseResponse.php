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
     * @var null|Model $singleObject
     */
    protected $singleObject = null;

    /**
     * @var array $arrayOfObjects
     */
    protected $arrayOfObjects = [];

    /**
     * @var array $mapping
     */
    protected $mapping = [];

    /**
     * @param Model $object
     */
    public function setSingleObject(Model $object)
    {
        $this->singleObject = $object;
    }

    public function setArrayOfObjects(array $objects)
    {
        $this->arrayOfObjects = $objects;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        $data = isset($this->arrayOfObjects) ?
            $this->mapArrayOfObjects($this->arrayOfObjects) :
            $this->mapSingleObject($this->singleObject);

        return response()->json($data);
    }

    /**
     * @param $arrayOfObjects
     *
     * @return array
     */
    private function mapArrayOfObjects($arrayOfObjects)
    {
        $data = [];
        foreach ($arrayOfObjects as $object) {
            $data[] = $this->mapSingleObject($object);
        }
        return $data;
    }

    /**
     * @param Model $object
     *
     * @return array
     */
    private function mapSingleObject(Model $object)
    {
        $output = [];

        //Get all the eager loaded relations
        $relations = $object->getRelations();

        //Apply mapping
        foreach ($this->mapping as $key => $value) {
            //If there is no attribute and no loaded relation, skip this mapping
            if (!Schema::hasColumn($object->getTable(), $value) &&
                !isset($relations[$value])
            ) {
                continue;
            }

            $output[$key] = $object->{$value};
        }

        return $output;
    }
}
