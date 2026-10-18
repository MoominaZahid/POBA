<?php

namespace Tests\Feature;

use App\Models\GalleryFolder;
use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryBannerImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
        Storage::fake('public');
    }

    private function createAdmin(): User
    {
        return User::create([
            'name'     => 'Gallery Super Admin',
            'email'    => 'gallery_banner_admin@poba.test',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);
    }

    private function createFakeImage(string $name = 'cover.png'): UploadedFile
    {
        $content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAA=');
        return UploadedFile::fake()->createWithContent($name, $content);
    }

    public function test_admin_can_create_gallery_folder_with_banner_image()
    {
        $admin = $this->createAdmin();
        $banner = $this->createFakeImage('custom-cover.png');

        $response = $this->actingAs($admin, 'web')->post(route('admin.gallery.store'), [
            'folder_name'  => 'Annual Reunion 2025',
            'class_year'   => 2025,
            'type'         => 'Public',
            'description'  => 'Reunion celebration gallery',
            'banner_image' => $banner,
        ]);

        $response->assertRedirect(route('admin.gallery.index'));

        $folder = GalleryFolder::where('folder_name', 'Annual Reunion 2025')->firstOrFail();
        $this->assertNotNull($folder->banner_image);
        Storage::assertExists($folder->banner_image);
        $this->assertEquals(media_url($folder->banner_image), $folder->card_image_url);
    }

    public function test_admin_can_update_and_replace_banner_image()
    {
        $admin = $this->createAdmin();
        $firstBanner = $this->createFakeImage('first-cover.png');

        $this->actingAs($admin, 'web')->post(route('admin.gallery.store'), [
            'folder_name'  => 'Spring Sports',
            'class_year'   => 2024,
            'type'         => 'Public',
            'description'  => 'Sports highlights',
            'banner_image' => $firstBanner,
        ]);

        $folder = GalleryFolder::where('folder_name', 'Spring Sports')->firstOrFail();
        $oldBannerPath = $folder->banner_image;
        Storage::assertExists($oldBannerPath);

        // Update with new banner
        $secondBanner = $this->createFakeImage('second-cover.png');
        $updateRes = $this->actingAs($admin, 'web')->put(route('admin.gallery.update', $folder->id), [
            'folder_name'  => 'Spring Sports Updated',
            'class_year'   => 2024,
            'type'         => 'Public',
            'description'  => 'Updated sports highlights',
            'banner_image' => $secondBanner,
        ]);
        $updateRes->assertRedirect(route('admin.gallery.index'));

        $folder->refresh();
        $this->assertNotEquals($oldBannerPath, $folder->banner_image);
        Storage::assertExists($folder->banner_image);
        Storage::assertMissing($oldBannerPath);
    }

    public function test_admin_can_remove_banner_image()
    {
        $admin = $this->createAdmin();
        $banner = $this->createFakeImage('cover-to-remove.png');

        $this->actingAs($admin, 'web')->post(route('admin.gallery.store'), [
            'folder_name'  => 'Farewell 2023',
            'class_year'   => 2023,
            'type'         => 'Public',
            'description'  => 'Farewell photos',
            'banner_image' => $banner,
        ]);

        $folder = GalleryFolder::where('folder_name', 'Farewell 2023')->firstOrFail();
        $bannerPath = $folder->banner_image;
        Storage::assertExists($bannerPath);

        // Remove banner via remove_banner_image flag
        $this->actingAs($admin, 'web')->put(route('admin.gallery.update', $folder->id), [
            'folder_name'         => 'Farewell 2023',
            'class_year'          => 2023,
            'type'                => 'Public',
            'description'         => 'Farewell photos',
            'remove_banner_image' => '1',
        ]);

        $folder->refresh();
        $this->assertNull($folder->banner_image);
        Storage::assertMissing($bannerPath);
    }

    public function test_gallery_list_page_displays_custom_banner_or_falls_back_to_first_photo()
    {
        // 1. Folder with custom banner
        $folderWithBanner = GalleryFolder::create([
            'folder_name'  => 'Golden Jubilee',
            'class_year'   => 2020,
            'type'         => 'Public',
            'description'  => 'Golden jubilee celebration',
            'slug'         => 'golden-jubilee',
            'banner_image' => 'gallery/custom-jubilee-banner.jpg',
        ]);

        // 2. Folder without banner, but with uploaded images
        $folderWithPhotos = GalleryFolder::create([
            'folder_name'  => 'Silver Jubilee',
            'class_year'   => 2019,
            'type'         => 'Public',
            'description'  => 'Silver jubilee celebration',
            'slug'         => 'silver-jubilee',
            'banner_image' => null,
        ]);
        GalleryImage::create([
            'gallery_folder_id' => $folderWithPhotos->id,
            'image_path'        => 'gallery/photo1.jpg',
            'sort_order'        => 1,
        ]);
        GalleryImage::create([
            'gallery_folder_id' => $folderWithPhotos->id,
            'image_path'        => 'gallery/photo2.jpg',
            'sort_order'        => 2,
        ]);

        // 3. Folder without banner and without photos
        $emptyFolder = GalleryFolder::create([
            'folder_name'  => 'Upcoming Batch',
            'class_year'   => 2026,
            'type'         => 'Public',
            'description'  => 'Upcoming events',
            'slug'         => 'upcoming-batch',
            'banner_image' => null,
        ]);

        // Visit list page
        $response = $this->get(route('gallery.index'));
        $response->assertStatus(200);

        // Folder with banner shows its custom banner
        $response->assertSee(media_url('gallery/custom-jubilee-banner.jpg'), false);

        // Folder without banner falls back to its first photo
        $response->assertSee(media_url('gallery/photo1.jpg'), false);

        // Empty folder falls back to placeholder
        $response->assertSee('https://placehold.co/400x220/e8f5f5/1a7a7a?text=Gallery', false);
    }

    public function test_destroy_gallery_folder_deletes_banner_image_from_storage()
    {
        $admin = $this->createAdmin();
        $banner = $this->createFakeImage('album-to-delete.png');

        $this->actingAs($admin, 'web')->post(route('admin.gallery.store'), [
            'folder_name'  => 'Temporary Folder',
            'class_year'   => 2024,
            'type'         => 'Public',
            'description'  => 'Will be deleted',
            'banner_image' => $banner,
        ]);

        $folder = GalleryFolder::where('folder_name', 'Temporary Folder')->firstOrFail();
        $bannerPath = $folder->banner_image;
        Storage::assertExists($bannerPath);

        $this->actingAs($admin, 'web')->delete(route('admin.gallery.destroy', $folder->id));

        $this->assertDatabaseMissing('gallery_folders', ['id' => $folder->id]);
        Storage::assertMissing($bannerPath);
    }
}
