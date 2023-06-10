<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;
use App\Http\Resources\CategoryCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CategoryRepository extends BaseRepository
{
    /**
     * TagRepository constructor.
     *
     * @param Tag $model
     */
    public function __construct()
    {
        parent::__construct(Category::class);
    }

    public function all() {
        $all = $this->baseQuery()->get();
        return new CategoryCollection($all);
    }

    public function paginate($perPage): CategoryCollection
    {
        $paginated =  $this->baseQuery()->paginate($perPage);
        return new CategoryCollection($paginated);
    }


    public function queryFilter()
    {
        return QueryBuilder::for($this->model)->allowedFilters([
          AllowedFilter::partial('s', 'name'),
        ]);
    }
}
