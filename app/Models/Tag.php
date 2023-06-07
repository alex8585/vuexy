<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model as Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory ;


    /* protected $sortFields = ['name']; */

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function scopeSort($query)
    {
        parent::scopeSort($query);

        $direction = request()->get('direction', 'asc');
        $order = request()->get('sortBy', 'id');

        $query->orderBy($order, $direction);
        
    }


}
