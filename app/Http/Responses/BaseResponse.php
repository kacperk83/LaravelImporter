<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;

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
    protected $arrayOfObjects = null;

    /**
     * @var array $mapping
     */
    protected $defaultMapping = [];

    /**
     * @var array $expandMapping
     */
    protected $expandMapping = [];

    /**
     * We want to output a single result
     *
     * @param Model $object
     */
    public function setSingleObject(Model $object)
    {
        $this->singleObject = $object;
    }

    /**
     * We want to output multiple results
     *
     * @param array $objects
     */
    public function setArrayOfObjects(array $objects)
    {
        $this->arrayOfObjects = $objects;
    }

    /**
     * Called by laravel while creating the response
     *
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
     * Map multiple results
     *
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
     * Map a single result
     *
     * @param Model $object
     *
     * @return array
     */
    private function mapSingleObject(Model $object)
    {
        $output = [];

        //Apply default mapping
        foreach ($this->defaultMapping as $key => $value) {
            $output[$key] = $object->{$value};
        }

        //Get all the eager loaded relations
        $relations = $object->getRelations();

        //Try to map every possible expand relation
        foreach ($this->expandMapping as $relation => $mappings) {
            //Skip relations which aren't loaded
            if (!isset($relations[$relation])) {
                continue;
            }
            //Map every entity in the relation
            foreach ($object->{$relation} as $relationEntity) {
                $mappedRelationEntity = [];
                //Apply all mappings for this relation entity
                foreach ($mappings as $key => $value) {
                    $mappedRelationEntity[$key] = $relationEntity->{$value};
                }
                $output[$relation][] = $mappedRelationEntity;
            }
        }

        return $output;
    }
}
