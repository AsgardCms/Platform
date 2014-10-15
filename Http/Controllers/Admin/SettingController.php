<?php namespace Modules\Setting\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Setting\Repositories\SettingRepository;

class SettingController extends AdminBaseController
{
    /**
     * @var SettingRepository
     */
    private $setting;

    public function __construct(SettingRepository $setting)
    {
        parent::__construct();

        $this->setting = $setting;
    }

    public function index()
    {
        return View::make('setting::admin.settings');
    }

    public function store()
    {

    }
}