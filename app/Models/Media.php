<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = ['id'];

    public function model()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute()
    {
        return \Storage::disk('s3')->url($this->fullFilename());
    }

    public function fullFilename(): string
    {
        return $this->filename . '.' . $this->extension;
    }
}
