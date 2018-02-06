<?php

namespace Tests\Unit\Models;

use App\Models\Media;
use App\Models\Play;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MediaTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_get_full_fillname()
    {
        $media = factory(Media::class)->make();

        $expected = $media->filename . '.' . $media->extension;

        $this->assertEquals($expected, $media->fullFilename());
    }

    /** @test */
    public function can_get_s3_url()
    {
        $media = factory(Media::class)->make();

        $expected = 'https://jmr-phone.s3-us-west-1.amazonaws.com/'.$media->fullFilename();
        $this->assertEquals($expected, $media->url);
    }
}
