<?php

namespace Tests\Feature;

use App\Models\GalleryFolder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GallerySlugPermanentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    private function createAdmin(): User
    {
        return User::create([
            'name'     => 'Gallery Super Admin',
            'email'    => 'gallery_admin@poba.test',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);
    }

    public function test_gallery_slug_is_generated_on_create_and_not_changed_on_update()
    {
        $admin = $this->createAdmin();

        // 1. Create gallery folder
        $createRes = $this->actingAs($admin, 'web')->post(route('admin.gallery.store'), [
            'folder_name' => 'Annual Gala 2024',
            'class_year'  => 2024,
            'type'        => 'Public',
            'description' => 'Annual gala celebration photos',
        ]);
        $createRes->assertRedirect(route('admin.gallery.index'));

        $folder = GalleryFolder::where('folder_name', 'Annual Gala 2024')->firstOrFail();
        $this->assertEquals('annual-gala-2024', $folder->slug);

        // 2. Update folder name and ensure slug does NOT change
        $updateRes = $this->actingAs($admin, 'web')->put(route('admin.gallery.update', $folder->id), [
            'folder_name' => 'Annual Gala 2024 - Revised Edition',
            'class_year'  => 2024,
            'type'        => 'Public',
            'description' => 'Updated celebration photos',
            'slug'        => 'attempted-new-slug-should-be-ignored',
        ]);
        $updateRes->assertRedirect(route('admin.gallery.index'));

        $folder->refresh();
        $this->assertEquals('Annual Gala 2024 - Revised Edition', $folder->folder_name);
        // Crucial requirement: Slug remains unchanged so shared links never break
        $this->assertEquals('annual-gala-2024', $folder->slug);
    }

    public function test_customer_gallery_detail_resolves_via_permanent_slug_without_extra_gallery_text()
    {
        $folder = GalleryFolder::create([
            'folder_name' => 'Sports Gala',
            'class_year'  => 2023,
            'type'        => 'Public',
            'description' => 'Sports gala event photos',
            'slug'        => 'sports-gala',
        ]);

        $response = $this->get(route('gallery.show', 'sports-gala'));
        $response->assertStatus(200);

        // Verify folder title is displayed without duplicate or extra "Gallery" inside h1
        $response->assertSee('<h1>Sports Gala</h1>', false);
        $response->assertDontSee('<h1>Sports Gala <span>Gallery</span></h1>', false);
        $response->assertDontSee('<h1>Sports Gala Gallery</h1>', false);

        // Verify there is only one back button (top left, not duplicated at the bottom)
        $content = $response->getContent();
        $backMatches = substr_count($content, 'class="btn-back"');
        $this->assertEquals(1, $backMatches, 'There should only be 1 back button on the gallery show page.');

        // Verify lightbox has controls container positioning arrows below image for mobile
        $response->assertSee('class="lbx-controls"', false);
        $response->assertSee('class="lbx-btn lbx-prev"', false);
        $response->assertSee('class="lbx-btn lbx-next"', false);
        $response->assertSee('class="lbx-counter"', false);
        $response->assertSee('@media (max-width: 768px)', false);
    }
}
