<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model as Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory ;


    protected $sortFields = ['name'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

}
