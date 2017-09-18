<?php

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Repositories\PageRepository;

class UpdatePageStatusController extends Controller
{
    /**
     * @var PageRepository
     */
    private $page;

    public function __construct(PageRepository $page)
    {
        $this->page = $page;
    }

    public function __invoke(Request $request)
    {
        $pageIds = json_decode($request->get('pageIds'));

        $this->handleAction($request->get('action'), $pageIds);

        return response()->json(['errors' => false, 'message' => trans('page::pages.pages were updated')]);
    }

    private function handleAction(string $action, array $pageIds)
    {
        if ($action === 'mark-online') {
            return $this->page->markMultipleAsOnlineInAllLocales($pageIds);
        }

        return $this->page->markMultipleAsOfflineInAllLocales($pageIds);
    }
}
