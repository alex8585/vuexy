<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Http\Resources\UserCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserRepository extends BaseRepository
{
    /**
     * TagRepository constructor.
     *
     * @param Tag $model
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }


    public function paginate($perPage): UserCollection
    {
        $paginated =  $this->baseQuery()->paginate($perPage);
        return new UserCollection($paginated);
    }

    public function baseQuery(): QueryBuilder
    {
        return $this->queryFilter()->sort();
    }

    public function queryFilter()
    {
        return QueryBuilder::for($this->model)->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('email'),
        ]);
    }
}
