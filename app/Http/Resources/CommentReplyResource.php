<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment_id' => $this->comment_id,
            'reader' => new ReaderResource($this->reader),
            'message' => $this->message,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
