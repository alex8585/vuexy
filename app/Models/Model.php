<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    protected $guarded = [];

    protected $sortFields = [];

    protected $casts = [
      'created_at' => 'date:d-m-Y H:i',
      'updated_at' => 'date:d-m-Y H:i',
    ];

    public function scopeSort($query)
    {
        $direction = request()->get('direction', 'asc');
        $order = request()->get('sortBy', 'id');

        $isIn = in_array($order, $this->sortFields);

        $query->when($isIn, function ($query) use ($order, $direction) {
            $query->orderBy($order, $direction);
        });
    }
}
