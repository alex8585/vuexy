<?php

namespace App\Models;

use App\Casts\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/* use Spatie\Tags\HasTags; */

class Post extends Model
{
    use HasFactory;
    use Prunable;


    protected $guarded = ['tags', 'category'];
    /* use HasTags; */
    protected $sortFields = ['id', 'title','description'];

    protected $casts = [
      'created_at' => Timestamp::class,
      'updated_at' => Timestamp::class . ':Y-m-d h:i:s',
    ];

    // #[SearchUsingPrefix(['title', 'description'])]
    //  #[SearchUsingFullText(['title', 'description'])]
    public function toSearchableArray(): array
    {
        return [
          'description' => $this->description,
          'title' => $this->title,
          'category' => $this->category->name,
          'tags' => $this->tags->makeHidden('pivot'),
        ];
    }

    public function searchableAs()
    {
        return 'posts_index';
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
          'name' => 'Default',
        ]);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }


    public function prunable()
    {
        return static::where('created_at', '<=', now()->subDays(3));
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with([
          'category',
          'tags' => function ($q) {
              /* $q->select(['id', 'name']); */
          },
        ]);
    }

}
