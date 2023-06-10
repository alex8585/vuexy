<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cat = new Category();

        $user = Auth::user();
        $can_create = $can_update = $can_delete = false;
        if ($user) {
            $can_create = $user->can('create', Category::class);
            $can_update = $user->can('update', $cat);
            $can_delete = $user->can('delete', $cat);
        }

        $meta = [
            'rowsPerPage' => -1,
            'rowsNumber' => 0,
            'pages' => 0,
            'page' => 0,
        ];

        if($this->resource instanceof LengthAwarePaginator) {
            $meta = [
                'rowsNumber' => $this->total(),
                'rowsPerPage' => $this->perPage(),
                'pages' => $this->lastPage(),
                'page' => $this->currentPage(),
                'can_create' => $can_create,
                'can_update' =>$can_update,
                'can_delete' =>$can_delete,
            ];
        }
    
        return [
          'data' => $this->collection,
          'metaData' => $meta,
        ];
    }

    public function withResponse($request, $response)
    {
        $jsonResponse = json_decode($response->getContent(), true);
        unset($jsonResponse['links'], $jsonResponse['meta']);
        $response->setContent(json_encode($jsonResponse));
    }
}
