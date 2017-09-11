<?php

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Page\Entities\Page;
use Modules\Page\Repositories\PageRepository;
use Modules\Page\Transformers\PageTransformer;

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

    public function index()
    {
        return PageTransformer::collection($this->page->all());
    }

    public function destroy(Page $page)
    {
        $this->page->destroy($page);

        return response()->json([
            'errors' => false,
            'message' => trans('page::messages.page deleted'),
        ]);
    }
}
