<?php

namespace Modules\Tag\Tests\Integration;

use Illuminate\Support\Facades\DB;
use Modules\Page\Entities\Page;
use Modules\Page\Repositories\PageRepository;
use Modules\Tag\Repositories\TagRepository;
use Modules\Tag\Tests\BaseTestCase;

class TaggableTraitTest extends BaseTestCase
{
    /**
     * @var TagRepository
     */
    private $tag;
    /**
     * @var PageRepository
     */
    private $page;

    public function setUp()
    {
        parent::setUp();

        $this->tag = app(TagRepository::class);
        $this->page = app(PageRepository::class);
    }

    /** @test */
    public function it_creates_tags_on_creation_of_related_model()
    {
        $page = $this->createPage();

        $page->setTags(['my first tag']);

        $this->assertCount(1, $this->tag->all());
    }

    /** @test */
    public function it_can_create_multiple_tags_at_once()
    {
        $page = $this->createPage();

        $page->setTags(['my first tag', 'second tag', 'third tag']);

        $this->assertCount(3, $this->tag->all());
    }

    /** @test */
    public function it_links_tags_to_entity()
    {
        $page = $this->createPage();

        $page->setTags(['my first tag']);

        $page = $this->page->findHomepage();

        $this->assertCount(1, $page->tags);
    }

    /** @test */
    public function it_removes_tags_from_link_with_entity()
    {
        $this->createPage(['original tag']);

        $page = $this->page->find(1);
        $page->setTags(['my first tag']);

        $this->assertCount(1, $page->tags);
    }

    /** @test */
    public function it_can_remove_all_tags()
    {
        $this->createPage(['original tag', 'tag2', 'tag3', ]);
        $page = $this->page->find(1);

        $this->page->update($page, ['tags' => []]);

        $page = $page->fresh();
        $this->assertCount(0, $page->tags);
    }

    /** @test */
    public function it_removes_all_tag_links_when_removing_entity()
    {
        $this->createPage(['original tag', 'tag2', 'tag3', ]);

        $page = $this->page->find(1);
        $pageId = $page->id;
        $this->page->destroy($page);

        $count = DB::table('tag__tagged')->where('taggable_id', $pageId)->get();

        $this->assertCount(0, $count);
    }

    /** @test */
    public function it_can_get_pages_with_one_of_specified_tags()
    {
        $this->createPage(['original tag']);
        $this->createPage(['original-tag']);
        $this->createPage(['random tag']);

        $this->assertCount(2, Page::withTag(['original-tag', 'some-other-tag'])->get());
    }

    /** @test */
    public function it_gets_pages_with_all_specified_tags()
    {
        $this->createPage(['original tag']);
        $this->createPage(['original-tag']);
        $this->createPage(['random tag', 'original-tag']);

        $this->assertCount(1, Page::whereTag(['original-tag', 'random-tag'])->get());
    }

    /** @test */
    public function it_gets_all_tags_for_a_namespace()
    {
        $this->createPage(['original tag', 'other tag', 'random tag']);

        $this->tag->create([
            'namespace' => 'asgardcms/media',
            'en' => [
                'slug' => 'media-tag',
                'name' => 'media tag',
            ],
        ]);

        $this->assertCount(3, Page::allTags()->get());
    }

    private function createPage(array $tags = [])
    {
        return $this->page->create([
            'is_home' => 1,
            'template' => 'default',
            'en' => [
                'title' => 'My Page',
                'slug' => 'my-page',
                'body' => 'My Page Body',
            ],
            'tags' => $tags,
        ]);
    }
}
