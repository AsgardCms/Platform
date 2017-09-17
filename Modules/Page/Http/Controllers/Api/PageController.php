<?php

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\Page;
use Modules\Page\Http\Requests\CreatePageRequest;
use Modules\Page\Http\Requests\UpdatePageRequest;
use Modules\Page\Repositories\PageRepository;
use Modules\Page\Transformers\FullPageTransformer;
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

    public function indexServerSide(Request $request)
    {
        return PageTransformer::collection($this->page->serverPaginationFilteringFor($request));
    }

    public function store(CreatePageRequest $request)
    {
        $this->page->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('page::messages.page created'),
        ]);
    }

    public function find(Page $page)
    {
        return new FullPageTransformer($page);
    }

    public function update(Page $page, UpdatePageRequest $request)
    {
        $this->page->update($page, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('page::messages.page updated'),
        ]);
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
