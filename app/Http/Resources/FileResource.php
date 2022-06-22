<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'filename' => $this->filename,
            'file_type' => $this->file_type,
            'mime_type' => $this->mime_type,
            'original_filename' => $this->original_file_name,
            'extension' => $this->extension,
            'size' => $this->size,
            'url' => $this->url,
            'date' => $this->date,
        ];
    }
}
