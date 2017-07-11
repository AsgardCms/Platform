<?php

namespace Modules\Page\Tests;

use Illuminate\Support\Facades\Event;
use Modules\Page\Events\ContentIsRendering;

class ContentIsRenderingTest extends BasePageTest
{
    /** @test */
    public function it_can_change_final_content()
    {
        Event::listen(ContentIsRendering::class, function (ContentIsRendering $event) {
            $event->setBody('<strong>' . $event->getOriginal() . '</strong>');
        });

        $page = $this->createPage();

        $this->assertEquals('<strong>My Page Body</strong>', $page->body);
    }

    /** @test */
    public function it_doesnt_alter_content_if_no_listeners()
    {
        $page = $this->createPage();

        $this->assertEquals('My Page Body', $page->body);
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
