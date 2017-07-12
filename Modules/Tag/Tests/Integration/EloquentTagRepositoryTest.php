<?php

namespace Modules\Tag\Tests\Integration;

use Illuminate\Support\Facades\Event;
use Modules\Tag\Events\TagIsCreating;
use Modules\Tag\Events\TagIsUpdating;
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
    public function it_triggers_event_when_tag_is_creating()
    {
        Event::fake();

        $tag = $this->tag->create([
            'namespace' => 'asgardcms/media',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);

        Event::assertDispatched(TagIsCreating::class, function ($e) use ($tag) {
            return $e->getAttribute('namespace') === $tag->namespace;
        });
    }

    /** @test */
    public function it_can_change_data_when_it_is_creating_event()
    {
        Event::listen(TagIsCreating::class, function (TagIsCreating $event) {
            $event->setAttributes(['en' => ['name' => 'MEDIA TAG']]);
        });

        $tag = $this->tag->create([
            'namespace' => 'asgardcms/media',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);

        $this->assertEquals('MEDIA TAG', $tag->translate('en')->name);
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

    /** @test */
    public function it_can_change_data_when_it_is_updating_event()
    {
        Event::listen(TagIsUpdating::class, function (TagIsUpdating $event) {
            $event->setAttributes(['en' => ['name' => 'MEDIA TAG']]);
        });

        $tag = $this->tag->create([
            'namespace' => 'asgardcms/media',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);
        $this->tag->update($tag, []);

        $this->assertEquals('MEDIA TAG', $tag->translate('en')->name);
    }
}
