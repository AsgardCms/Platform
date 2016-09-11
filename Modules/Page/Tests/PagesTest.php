<?php

namespace Modules\Page\Tests;

class PagesTest extends BasePageTest
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
}
