<?php

namespace App\Repositories;

use App\Http\Controllers\V1\BaseController;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BaseRepository
 *
 * @package App\Repositories
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class BaseRepository
{
    /**
     * @var Model $model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get single result
     *
     * @param int   $id
     * @param array $queryParams
     *
     * @return mixed
     */
    public function get(int $id, array $queryParams)
    {
        $builder = $this->model->newQuery();
        $builder = $this->processQueryParams($queryParams, $builder);

        return $builder->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get multiple results
     *
     * @param array $queryParams
     *
     * @return Collection
     */
    public function getAll(array $queryParams = [])
    {
        $builder = $this->model->newQuery();
        $builder = $this->processQueryParams($queryParams, $builder);

        return Collection::make($builder->get());
    }

    /**
     * Process the possible query params (limit, offset & expand)
     *
     * @param array $queryParams
     * @param       $builder
     *
     * @return mixed
     */
    protected function processQueryParams(array $queryParams, Builder $builder)
    {
        $builder = $this->processLimitOffsetParams($queryParams, $builder);

        $builder = $this->processExpandParams($queryParams, $builder);

        return $builder;
    }

    /**
     * Add the expand to the query builder
     *
     * @param array $queryParams
     * @param       $builder
     *
     * @return mixed
     */
    private function processExpandParams(array $queryParams, Builder $builder)
    {
        if (isset($queryParams[BaseController::EXPAND])) {
            $builder->with($queryParams[BaseController::EXPAND]);
        }
        return $builder;
    }

    /**
     * Add the limit & offset to the query builder
     *
     * @param array   $queryParams
     * @param Builder $builder
     *
     * @return Builder|\Illuminate\Database\Query\Builder|static
     */
    private function processLimitOffsetParams(array $queryParams, Builder $builder)
    {
        if (isset($queryParams[BaseController::LIMIT])
            && is_numeric($queryParams[BaseController::LIMIT])
            && isset($queryParams[BaseController::OFFSET])
            && is_numeric($queryParams[BaseController::OFFSET])
        ) {
            return $builder->take(
                (int)$queryParams[BaseController::LIMIT]
            )->skip(
                (int)$queryParams[BaseController::OFFSET]
            );
        } else {
            return $builder;
        }
    }
}
