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
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;

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
        $pages = $this->page->allWithBuilder();
//        $test = new EloquentDataTable(Page::query());
//        dd($test->make());
//        $test = DataTables::eloquent(Page::query())->make();
//        dd($test);


        if ($request->has('search')) {
            $term = $request->get('search');
            $pages->whereHas('translations', function($query) use($term) {
                $query->where('title', 'LIKE', "%{$term}%");
                $query->orWhere('slug', 'LIKE', "%{$term}%");
            })
                ->orWhere('id', $term);
        }

        if ($request->has('order_by') && $request->get('order_by') !== 'undefined' && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            if (str_contains($request->get('order_by'), '.')) {
                $fields = explode('.', $request->get('order_by'));

                $pages->join('page__page_translations as t', function ($join) {
                    $join->on('page__pages.id', '=', 't.page_id')
                        ->where('t.locale', locale());
                })
                    ->groupBy('page__pages.id')->orderBy("t.{$fields[1]}", $order);
            } else {
                $pages->orderBy($request->get('order_by'), $order);
            }
        }

        return PageTransformer::collection($pages->paginate($request->get('per_page', 10)));
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
