<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Facades\TransHelp;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;


    protected $sortFields = ['name'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
