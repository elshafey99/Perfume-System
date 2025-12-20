<?php

namespace App\Http\Resources\Poll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\FileHelper;

class PollAttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'poll_id' => $this->poll_id,
            'file_name' => $this->file_name,
            'file_url' => FileHelper::url($this->file_path),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
