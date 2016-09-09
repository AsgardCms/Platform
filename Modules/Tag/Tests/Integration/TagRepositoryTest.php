<?php

namespace Modules\Tag\Tests\Integration;

use Modules\Tag\Repositories\TagRepository;
use Modules\Tag\Tests\BaseTestCase;

class TagRepositoryTest extends BaseTestCase
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
}
