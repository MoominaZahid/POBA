<?php

namespace Tests\Feature;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsLazyLoaderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    public function test_news_page_renders_initial_batch_with_load_more_button_and_lazy_images()
    {
        // Create 15 news items (page size is 12)
        for ($i = 1; $i <= 15; $i++) {
            News::create([
                'title'        => "News Headline {$i}",
                'type'         => 'news',
                'description'  => "Description for news {$i}",
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get(route('news.index'));
        $response->assertStatus(200);

        // Initial page renders first 12 items
        $response->assertSee('News Headline 1');
        $response->assertSee('News Headline 12');
        $response->assertDontSee('News Headline 13');

        // Check Load More button and lazy attribute
        $response->assertSee('id="btnLoadMore"', false);
        $response->assertSee('data-next-page="2"', false);
        $response->assertSee('loading="lazy"', false);
    }

    public function test_news_page_returns_json_for_ajax_request()
    {
        for ($i = 1; $i <= 18; $i++) {
            News::create([
                'title'        => "News Item {$i}",
                'type'         => 'news',
                'description'  => "Body for news {$i}",
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->getJson(route('news.index', ['page' => 2]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'html',
            'hasMore',
            'nextPage',
        ]);

        $data = $response->json();
        $this->assertStringContainsString('News Item 13', $data['html']);
        $this->assertFalse($data['hasMore']);
        $this->assertNull($data['nextPage']);
    }

    public function test_news_page_hides_load_more_button_when_items_fit_in_single_page()
    {
        for ($i = 1; $i <= 5; $i++) {
            News::create([
                'title'        => "Single Page News {$i}",
                'type'         => 'news',
                'description'  => "Short body {$i}",
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get(route('news.index'));
        $response->assertStatus(200);
        $response->assertSee('Single Page News 1');
        $response->assertSee('display:none;', false);
    }
}
