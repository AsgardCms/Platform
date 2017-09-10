<?php

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Page\Entities\Page;
use Modules\Page\Repositories\PageRepository;

class PageController extends Controller
{
    /**
     * @var PageRepository
     */
    private $page;

    public function __construct(PageRepository $page)
    {
        $this->page = $page;
    }

    public function destroy($page)
    {
        $this->page->destroy($page);

        return response()->json([
            'errors' => false,
            'message' => trans('page::messages.page deleted'),
        ]);
    }
}
