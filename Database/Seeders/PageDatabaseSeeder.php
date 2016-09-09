<?php

namespace Modules\Page\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Page\Repositories\PageRepository;

class PageDatabaseSeeder extends Seeder
{
    /**
     * @var PageRepository
     */
    private $page;

    public function __construct(PageRepository $page)
    {
        $this->page = $page;
    }

    public function run()
    {
        Model::unguard();

        $data = [
            'template' => 'home',
            'is_home' => 1,
            'en' => [
                'title' => 'Home page',
                'slug' => 'home',
                'body' => '<p><strong>You made it!</strong></p>
<p>You&#39;ve installed AsgardCMS and are ready to proceed to the <a href="/en/backend">administration area</a>.</p>
<h2>What&#39;s next ?</h2>
<p>Learn how you can develop modules for AsgardCMS by reading our <a href="https://github.com/AsgardCms/Documentation">documentation</a>.</p>
',
                'meta_title' => 'Home page',
            ],
        ];
        $this->page->create($data);
    }
}
