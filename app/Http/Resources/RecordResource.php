<?php

namespace App\Http\Resources;

use App\Models\Record;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      @OA\Property(property="id", type="string", example="1"),
 *      @OA\Property(property="name", type="string", example="name"),
 *      @OA\Property(property="type", type="string", example="1"),
 *      @OA\Property(property="description", type="string", example="description"),
 *      @OA\Property(property="file", type="string", example="http:\/\/localhost:2715\/api\/img\/sgGtJnDvbDS5a7f7")
 *  )
 *
 * @property Record $resource
 */
class RecordResource extends JsonResource
{
    public function toArray($request): array
    {
        if ($request->getMethod() === 'POST') {
            return [
                'name' => $this->resource->name,
                'type' => $this->resource->type,
                'description' => $this->resource->description,
            ];
        }
        if ($this->resource->img) {
            return [
                'id' => $this->resource->getKey(),
                'name' => $this->resource->name,
                'type' => $this->resource->type,
                'description' => $this->resource->description,
                'file' => $this->resource->img,
            ];
        }

        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->name,
            'type' => $this->resource->type,
            'description' => $this->resource->description,
        ];
    }
}
