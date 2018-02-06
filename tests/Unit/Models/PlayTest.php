<?php

namespace Tests\Unit\Models;

use App\Models\Media;
use App\Models\Play;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlayTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_get_media()
    {
        $company = $this->createCompany();
        $play = factory(Play::class)->create([
            'company_id' => $company->id,
        ]);

        $media = factory(Media::class)->create([
            'company_id' => $company->id,
            'model_type' => 'play',
            'model_id' => $play->id,
        ]);

        $this->assertInstanceOf(Media::class, $play->media);
    }

    /** @test */
    public function get_noun_attribute()
    {
        $company = $this->createCompany();
        $play = factory(Play::class)->create([
            'company_id' => $company->id,
        ]);

        $media = factory(Media::class)->create([
            'company_id' => $company->id,
            'model_type' => 'play',
            'model_id' => $play->id,
        ]);

        $this->assertEquals($media->url, $play->noun);
    }

    /** @test */
    public function get_noun_attribute_is_null_if_media_not_set()
    {
        $company = $this->createCompany();
        $play = factory(Play::class)->create([
            'company_id' => $company->id,
        ]);

        $this->assertNull($play->noun);
    }

    /** @test */
    public function can_create_media()
    {
        $file = UploadedFile::fake()->create('temp.wav', 500);

        $company = $this->createCompany();

        $play = factory(Play::class)->create([
            'company_id' => $company->id,
        ]);

        $media = $play->createMedia($file);

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
        ]);

        \Storage::disk('s3')->delete($media->fullFilename());
    }

    /** @test */
    public function can_create_media_and_upload_to_s3()
    {
        $file = UploadedFile::fake()->create('temp.wav', 500);

        $company = $this->createCompany();

        $play = factory(Play::class)->create([
            'company_id' => $company->id,
        ]);

        $media = $play->createMedia($file);

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
        ]);

        \Storage::disk('s3')->assertExists($media->fullFilename());

        \Storage::disk('s3')->delete($media->fullFilename());
    }

    /** @test */
    public function can_update_media()
    {
        $file = UploadedFile::fake()->create('temp.wav', 500);

        $company = $this->createCompany();

        $play = factory(Play::class)->create([
            'company_id' => $company->id,
        ]);

        $media = $play->createMedia($file);

        $newMedia = $play->updateMedia($file);

        $this->assertDatabaseHas('media', [
            'id' => $newMedia->id,
            'model_id' => $play->id,
            'filename' => $newMedia->filename
        ]);

        $this->assertDatabaseMissing('media', [
            'model_id' => $play->id,
            'filename' => $media->filename
        ]);

        \Storage::disk('s3')->assertExists($newMedia->fullFilename());

        \Storage::disk('s3')->delete($media->fullFilename());
        \Storage::disk('s3')->delete($newMedia->fullFilename());
    }

    /** @test */
    public function update_media_will_create_media_if_no_media_exist()
    {
        $file = UploadedFile::fake()->create('temp.wav', 500);

        $company = $this->createCompany();

        $play = factory(Play::class)->create([
            'company_id' => $company->id,
        ]);

        $media = $play->updateMedia($file);

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
        ]);

        \Storage::disk('s3')->assertExists($media->fullFilename());

        \Storage::disk('s3')->delete($media->fullFilename());
    }
}
