<?php
namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
* Interface EloquentRepositoryInterface
* @package App\Repositories
*/
interface EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    public function update(Model $model, array $attributes): bool;
    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model;

    public function queryFilter();
}
