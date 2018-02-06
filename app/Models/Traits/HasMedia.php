<?php

namespace App\Models\Traits;

use App\Models\Media;
use Symfony\Component\HttpFoundation\File\File;

trait HasMedia
{
    public function media()
    {
        return $this->morphOne(Media::class, 'model');
    }

    public function createMedia(File $file): Media
    {
        $media = $this->media()->create([
            'filename' => str_random(32),
            'extension' => $file->guessExtension(),
            'company_id' => $this->company_id,
        ]);

        \Storage::disk('s3')->putFileAs('', $file, $media->fullFilename(), 'public');

        return $media;
    }

    public function updateMedia(File $file)
    {
        if (is_null($this->media)) {
            return $this->createMedia($file);
        }

        $media = tap($this->media)->update([
            'filename' => str_random(32),
            'extension' => $file->guessExtension(),
        ]);

        \Storage::disk('s3')->putFileAs('', $file, $media->fullFilename(), 'public');

        return $media;
    }
}
