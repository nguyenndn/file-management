<?php

use GGPHP\FileMedia\Models\FileMedia;
use GGPHP\FileMedia\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\FileUploader;

class UploadFileTest extends TestCase
{
    /**
     * Test configuration creation function.
     */
    public function testUploadSingleFile()
    {
        Storage::fake('test');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->call('POST', '/api/file/upload', ['file' => [$file]], ['Accept' => 'application/form-data']);

        $content = json_decode($response->getContent());

        $response->assertStatus(200);
        $this->assertDatabaseHas('file_media', [
            'file_name_original' => 'test.jpg',
        ]);

        $this->assertEquals(1, FileMedia::count());
    }

    /**
     * Test configuration creation function.
     */
    public function testUploadMultipleFile()
    {
        Storage::fake('test');

        $firstFile  = UploadedFile::fake()->create('test1.jpg');
        $secondFile  = UploadedFile::fake()->create('test2.jpg');

        $response = $this->call('POST', '/api/file/upload', ['file' => [$firstFile, $secondFile]], ['Accept' => 'application/form-data']);
        $content = json_decode($response->getContent());

        $response->assertStatus(200);
        $this->assertDatabaseHas('file_media', [
            'file_name_original' => 'test1.jpg',
        ]);
        $this->assertDatabaseHas('file_media', [
            'file_name_original' => 'test2.jpg',
        ]);

        $this->assertEquals(2, FileMedia::count());
    }

    /**
     * Test configuration creation function.
     */
    public function testDeleteFile()
    {
        Storage::fake('test');

        $firstFile  = UploadedFile::fake()->create('test1.jpg');

        $response = $this->call('POST', '/api/file/upload', ['file' => [$firstFile]], ['Accept' => 'application/form-data']);

        $fileExist = FileMedia::first();

        $responseDelete = $this->call('DELETE', '/api/file/delete/' . $fileExist->id, [], []);

        $content = json_decode($responseDelete->getContent());

        $response->assertStatus(200);
        $this->assertDatabaseMissing('file_media', [
            'file_name_original' => 'test1.jpg',
        ]);

        $this->assertEquals(0, FileMedia::count());
    }
}
