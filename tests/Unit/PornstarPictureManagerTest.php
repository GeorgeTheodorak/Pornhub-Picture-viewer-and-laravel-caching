<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage as StorageAlias;
use Illuminate\Support\Facades\File;
use App\Managers\PornstarPictureManager;
use App\Enums\PictureType;
use App\Utils\PictureUtil;
use Mockery;
use Illuminate\Support\Facades\Http;

class PornstarPictureManagerTest extends TestCase
{
    protected function tearDown(): void
    {
        // Close Mockery to prevent memory leaks
        Mockery::close();
    }

    public function test_it_saves_and_deletes_previous_picture(): void
    {
        // Mock the Storage facade
        StorageAlias::fake('media');

        // Mock the PictureUtil class to skip the actual deletion
        $pictureUtilMock = Mockery::mock('alias:App\Utils\PictureUtil');
        $pictureUtilMock->shouldReceive('deleteAllFilesInFolder')
            ->once()
            ->with('pornstars/thumbnails/000000123/device_type_value/')
            ->andReturn(true);

        // Arrange: Prepare manager, data, and dependencies
        $manager = new PornstarPictureManager();
        $pornstarId = 123;
        $pictureData = [
            'deviceType' => PictureType::PC->value,
            'width' => 1920,
            'height' => 1080,
            'base64' => 'fake_base64_image_data',
        ];

        // Act: Save picture using the manager
        $manager->savePicture($pornstarId, $pictureData);

        // Assert: Check that a file was saved with the correct path and contents
        StorageAlias::disk('media')->assertExists('pornstars/thumbnails/000000123/device_type_value/w_1920_h_1080.jpg');
        StorageAlias::disk('media')->assertFileEquals(
            'pornstars/thumbnails/000000123/device_type_value/w_1920_h_1080.jpg',
            'fake_base64_image_data'
        );
    }

    public function test_it_pads_pornstar_id_and_saves_picture(): void
    {
        // Mock the Storage facade
        StorageAlias::fake('media');

        // Mock the PictureUtil class to skip the actual deletion
        $pictureUtilMock = Mockery::mock('alias:App\Utils\PictureUtil');

        $pictureUtilMock->shouldReceive('deleteAllFilesInFolder')
            ->once()
            ->with('pornstars/thumbnails/000000123/pc/')
            ->andReturn(true);

        // Arrange: Prepare manager, data, and dependencies
        $manager = new PornstarPictureManager();
        $pornstarId = 123;
        $pictureData = [
            'deviceType' => PictureType::PC->value, // Adjust according to the actual enum value
            'width' => 1920,
            'height' => 1080,
            'base64' => 'fake_base64_image_data', // This would be the base64 string representing an image
        ];

        // Act: Save picture using the manager
        $manager->savePicture($pornstarId, $pictureData);

        // Assert: Check that a file was saved with the correct path
        StorageAlias::disk('media')->assertExists('pornstars/thumbnails/000000123/pc/w_1920_h_1080.jpg');
        StorageAlias::disk('media')->assertMissing('pornstars/thumbnails/000000123/pc/w_1920_h_1080.jpg');
    }

    /**
     * Utility to invoke private methods for testing
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
