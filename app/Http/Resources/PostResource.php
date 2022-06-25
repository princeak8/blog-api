<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\PostTagResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CategoryResource;

class PostResource extends JsonResource
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
            // 'user' => $this->user,
            'title' => $this->title,
            'content' => $this->content,
            'category' => new CategoryResource($this->category),
            'tags' => PostTagResource::collection($this->tags),
            'published' => ($this->published==1) ? True : False,
            'visible' => ($this->visible==1) ? True : False,
            'views_count' => $this->views_count,
            'comments_count' => $this->comments,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'edited' => ($this->created_at != $this->updated_at),
            'published_at' => ($this->published_at) ? $this->published_at->diffForHumans() : null,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
