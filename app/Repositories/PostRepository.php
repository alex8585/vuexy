<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Collection;
use App\Http\Resources\PostCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Spatie\QueryBuilder\QueryBuilder;
class PostRepository extends BaseRepository
{
    /**
     * PostRepository constructor.
     *
     * @param Post $model
     */
    public function __construct()
    {
        parent::__construct(Post::class);
    }
     /**
     * @return Collection
     */
    public function search($q, $perPage): PostCollection
    {
        $hits = $this->model->search($q)->raw()['hits'];
        $postsIds = array_column($hits, 'id');

        $paginated = $this->baseQuery()->whereIn('id', $postsIds)->paginate($perPage);
        return new PostCollection($paginated);
    }

    public function paginate($perPage): PostCollection
    {
        $paginated =  $this->baseQuery()->paginate($perPage);
        return new PostCollection($paginated);
    }

    public function create(array $attributes, $tags = []): Model
    {
        $post =  $this->model->create($attributes);

        if ($tags) {
            if (isset($tags[0]['value'])) {
                $tagsIds = collect($tags)->pluck('value');
            } else {
                $tagsIds = collect($tags);
            }
            $post->tags()->sync($tagsIds);
        }
        return $post;
    }

    public function update(Model $post, array $attributes, $tags = []): bool
    {
        $result = $post->update($attributes);
        if ($tags) {
            if (isset($tags[0]['value'])) {
                $tagsIds = collect($tags)->pluck('value');
            } else {
                $tagsIds = collect($tags);
            }
            $post->tags()->sync($tagsIds);
        }

        return $result;
    }

    public function baseQuery(): QueryBuilder
    {
        return $this->queryFilter()
            ->with(['tags','category'])
            ->sort();
    }

    public function queryFilter()
    {
        $s = request('filter.s');

        return QueryBuilder::for($this->model)
            ->when($s, function ($query) use($s) {
                    $query->where('title', 'like', "%$s%")
                      ->orWhere('description', 'like', "%$s%");
        });
                                              
                                              
    }
}
