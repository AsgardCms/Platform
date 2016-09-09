<?php

namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Modules\Menu\Repositories\MenuItemRepository;

class PublicMiddleware
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var MenuItemRepository
     */
    private $menuItem;

    public function __construct(Request $request, MenuItemRepository $menuItem)
    {
        $this->request = $request;
        $this->menuItem = $menuItem;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $locale = $this->request->segment(1) ?: App::getLocale();
        $item = $this->menuItem->findByUriInLanguage($this->request->segment(2), $locale);

        if ($this->isOffline($item)) {
            App::abort(404);
        }

        return $next($request);
    }

    /**
     * Checks if the given menu item is offline
     * @param  object $item
     * @return bool
     */
    private function isOffline($item)
    {
        return is_null($item);
    }
}
