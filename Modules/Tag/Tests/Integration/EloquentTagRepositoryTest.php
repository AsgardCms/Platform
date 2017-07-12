<?php

namespace Modules\Tag\Tests\Integration;

use Illuminate\Support\Facades\Event;
use Modules\Tag\Events\TagWasCreated;
use Modules\Tag\Events\TagWasUpdated;
use Modules\Tag\Repositories\TagRepository;
use Modules\Tag\Tests\BaseTestCase;

class EloquentTagRepositoryTest extends BaseTestCase
{
    /**
     * @var TagRepository
     */
    private $tag;

    public function setUp()
    {
        parent::setUp();

        $this->tag = app(TagRepository::class);
    }

    /** @test */
    public function it_gets_all_tags_for_a_namespace()
    {
        $this->tag->create([
            'namespace' => 'asgardcms/media',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);
        $this->tag->create([
            'namespace' => 'asgardcms/media',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);
        $this->tag->create([
            'namespace' => 'asgardcms/blog',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);

        $this->assertCount(1, $this->tag->allForNamespace('asgardcms/blog'));
    }

    /** @test */
    public function it_triggers_event_when_tag_was_created()
    {
        Event::fake();

        $tag = $this->tag->create([
            'namespace' => 'asgardcms/media',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);

        Event::assertDispatched(TagWasCreated::class, function ($e) use ($tag) {
            return $e->tag->id === $tag->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_tag_was_updated()
    {
        Event::fake();

        $tag = $this->tag->create([
            'namespace' => 'asgardcms/media',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);
        $this->tag->update($tag, []);

        Event::assertDispatched(TagWasUpdated::class, function ($e) use ($tag) {
            return $e->tag->id === $tag->id;
        });
    }
}
