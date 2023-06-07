<?php

namespace App\Repositories;

use App\Interfaces\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseRepository implements EloquentRepositoryInterface
{

    /**
     * @var Model
     */
    protected $model;
    private string $modelClass;
    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(?string $modelClass = null)
    {
        $this->modelClass = $modelClass ?: self::guessModelClass();
        $this->model = app($this->modelClass);
    }

    private static function guessModelClass(): string
    {
        return preg_replace('/(.+)\\\\Repositories\\\\(.+)Repository$/m', '$1\Models\\\$2', static::class);
    }


    abstract public function queryFilter();

    public function baseQuery(): QueryBuilder
    {
        return $this->queryFilter()->sort();
    }

    /**
     * @return Collection
     */
    public function all() 
    {
        return $this->baseQuery()->get();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        /* $data = $this->formatLocalesFields($attributes); */
        return $this->model->create($attributes);
    }

    public function update(Model $model, array $attributes): bool
    {
        /* $data = $this->formatLocalesFields($attributes); */
        return $model->update($attributes);
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }
}
