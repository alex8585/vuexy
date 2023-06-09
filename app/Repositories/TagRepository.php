<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Support\Collection;
use App\Http\Resources\TagCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TagRepository extends BaseRepository
{
    /**
     * TagRepository constructor.
     *
     * @param Tag $model
     */
    public function __construct()
    {
        parent::__construct(Tag::class);
    }


    public function all() {
        $all = $this->baseQuery()->get();
        return new TagCollection($all);
    }

    public function paginate($perPage): TagCollection
    {
        $paginated =  $this->baseQuery()->paginate($perPage);
        return new TagCollection($paginated);
    }

    public function queryFilter()
    {
        return QueryBuilder::for($this->model)->allowedFilters([
          AllowedFilter::partial('s', 'name'),
        ]);
    }

}
