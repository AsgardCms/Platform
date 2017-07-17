<?php

namespace Modules\Page\Tests;

use Illuminate\Support\Facades\Event;
use Modules\Page\Events\PageIsCreating;
use Modules\Page\Events\PageIsUpdating;
use Modules\Page\Events\PageWasCreated;
use Modules\Page\Events\PageWasDeleted;
use Modules\Page\Events\PageWasUpdated;

class EloquentPageRepositoryTest extends BasePageTest
{
    /** @test */
    public function it_makes_page_as_homepage()
    {
        $page = $this->page->create([
            'is_home' => 1,
            'template' => 'default',
            'en' => [
                'title' => 'My Page',
                'slug' => 'my-page',
                'body' => 'My Page Body',
            ],
        ]);

        $homepage = $this->page->findHomepage();

        $this->assertTrue($page->is_home);
        $this->assertEquals($page->id, $homepage->id);
    }

    /** @test */
    public function it_can_unset_homepage()
    {
        $page = $this->page->create([
            'is_home' => 1,
            'template' => 'default',
            'en' => [
                'title' => 'My Page',
                'slug' => 'my-page',
                'body' => 'My Page Body',
            ],
        ]);
        $page = $this->page->update($page, [
            'is_home' => 0,
        ]);
        $this->assertFalse($page->is_home);
    }

    /** @test */
    public function it_unsets_first_homepage_if_another_is_set_as_homepage()
    {
        $this->page->create([
            'is_home' => '1',
            'template' => 'default',
            'en' => [
                'title' => 'My Page',
                'slug' => 'my-page',
                'body' => 'My Page Body',
            ],
        ]);
        $pageOther = $this->page->create([
            'is_home' => '1',
            'template' => 'default',
            'en' => [
                'title' => 'My Other Page',
                'slug' => 'my-other-page',
                'body' => 'My Page Body',
            ],
        ]);

        $page = $this->page->find(1);
        $this->assertFalse($page->is_home);
        $this->assertTrue($pageOther->is_home);
    }

    /** @test */
    public function it_triggers_event_when_page_was_created()
    {
        Event::fake();

        $page = $this->page->create([
            'is_home' => '1',
            'template' => 'default',
            'en' => [
                'title' => 'My Other Page',
                'slug' => 'my-other-page',
                'body' => 'My Page Body',
            ],
        ]);

        Event::assertDispatched(PageWasCreated::class, function ($e) use ($page) {
            return $e->pageId === $page->id;
        });
    }

    /** @test */
    public function it_triggers_an_event_when_page_is_creating()
    {
        Event::fake();

        $page = $this->createPage();

        Event::assertDispatched(PageIsCreating::class, function ($e) use ($page) {
            return $e->getAttribute('template') === $page->template;
        });
    }

    /** @test */
    public function it_can_change_page_data_before_creating_page()
    {
        Event::listen(PageIsCreating::class, function (PageIsCreating $event) {
            $event->setAttributes(['template' => 'better-tpl']);
        });

        $page = $this->createPage();

        $this->assertEquals('better-tpl', $page->template);
    }

    /** @test */
    public function it_triggers_an_event_when_page_is_updating()
    {
        Event::fake();
        $page = $this->createPage();

        $this->page->update($page, ['en' => ['title' => 'Better!']]);

        Event::assertDispatched(PageIsUpdating::class, function ($e) use ($page) {
            return $e->getPage()->id === $page->id;
        });
    }

    /** @test */
    public function it_can_change_page_data_before_updating_page()
    {
        Event::listen(PageIsUpdating::class, function (PageIsUpdating $event) {
            $event->setAttributes(['template' => 'better-tpl']);
        });

        $page = $this->createPage();
        $this->page->update($page, ['template' => 'my-template', 'en' => ['title' => 'Better!']]);

        $this->assertEquals('better-tpl', $page->template);
    }

    /** @test */
    public function it_triggers_event_when_page_was_updated()
    {
        $page = $this->page->create([
            'is_home' => '1',
            'template' => 'default',
            'en' => [
                'title' => 'My Other Page',
                'slug' => 'my-other-page',
                'body' => 'My Page Body',
            ],
        ]);
        Event::fake();

        $this->page->update($page, ['en' => ['title' => 'Better!']]);

        Event::assertDispatched(PageWasUpdated::class, function ($e) use ($page) {
            return $e->pageId === $page->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_page_was_deleted()
    {
        $page = $this->page->create([
            'is_home' => '1',
            'template' => 'default',
            'en' => [
                'title' => 'My Other Page',
                'slug' => 'my-other-page',
                'body' => 'My Page Body',
            ],
        ]);

        Event::fake();

        $this->page->destroy($page);

        Event::assertDispatched(PageWasDeleted::class, function ($e) use ($page) {
            return $e->page->id === $page->id;
        });
    }

    private function createPage()
    {
        return $this->page->create([
            'is_home' => '1',
            'template' => 'default',
            'en' => [
                'title' => 'My Other Page',
                'slug' => 'my-other-page',
                'body' => 'My Page Body',
            ],
        ]);
    }
}
