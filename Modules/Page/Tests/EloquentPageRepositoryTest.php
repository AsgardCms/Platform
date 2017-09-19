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
            return $e->page->id === $page->id;
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
            return $e->page->id === $page->id;
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

    /** @test */
    public function it_can_mark_page_as_online_in_all_locales()
    {
        $page = $this->createRandomOfflinePage();

        $page = $this->page->markAsOnlineInAllLocales($page);

        $this->assertTrue($page->translate('en')->status);
        $this->assertTrue($page->translate('fr')->status);
    }

    /** @test */
    public function it_can_mark_multiple_pages_as_online()
    {
        $pageOne = $this->createRandomOfflinePage();
        $pageTwo = $this->createRandomOfflinePage();

        $this->page->markMultipleAsOnlineInAllLocales([1,2]);

        $pageOne->refresh();
        $pageTwo->refresh();

        $this->assertTrue($pageOne->translate('en')->status);
        $this->assertTrue($pageOne->translate('fr')->status);
        $this->assertTrue($pageTwo->translate('en')->status);
        $this->assertTrue($pageTwo->translate('fr')->status);
    }

    /** @test */
    public function it_can_mark_page_as_offline_in_all_locales()
    {
        $page = $this->createRandomOnlinePage();

        $page = $this->page->markAsOfflineInAllLocales($page);

        $this->assertFalse($page->translate('en')->status);
        $this->assertFalse($page->translate('fr')->status);
    }

    /** @test */
    public function it_can_mark_multiple_pages_as_offline()
    {
        $pageOne = $this->createRandomOnlinePage();
        $pageTwo = $this->createRandomOnlinePage();

        $this->page->markMultipleAsOfflineInAllLocales([1,2]);

        $pageOne->refresh();
        $pageTwo->refresh();

        $this->assertFalse($pageOne->translate('en')->status);
        $this->assertFalse($pageOne->translate('fr')->status);
        $this->assertFalse($pageTwo->translate('en')->status);
        $this->assertFalse($pageTwo->translate('fr')->status);
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

    private function createRandomOfflinePage()
    {
        $faker = \Faker\Factory::create();

        $data = [
            'is_home' => 0,
            'template' => 'default',
            'en' => [
                'status' => 0,
                'title' => $faker->name,
                'slug' => $faker->slug,
                'body' => $faker->paragraph(),
            ],
            'fr' => [
                'status' => 0,
                'title' => $faker->name,
                'slug' => $faker->slug,
                'body' => $faker->paragraph(),
            ],
        ];

        return $this->page->create($data);
    }

    private function createRandomOnlinePage()
    {
        $faker = \Faker\Factory::create();

        $data = [
            'is_home' => 0,
            'template' => 'default',
            'en' => [
                'status' => 1,
                'title' => $faker->name,
                'slug' => $faker->slug,
                'body' => $faker->paragraph(),
            ],
            'fr' => [
                'status' => 1,
                'title' => $faker->name,
                'slug' => $faker->slug,
                'body' => $faker->paragraph(),
            ],
        ];

        return $this->page->create($data);
    }
}
