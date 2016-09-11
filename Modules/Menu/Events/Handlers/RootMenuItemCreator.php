<?php

namespace Modules\Menu\Events\Handlers;

use Modules\Menu\Events\MenuWasCreated;
use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Setting\Contracts\Setting;

class RootMenuItemCreator
{
    /**
     * @var MenuItemRepository
     */
    private $menuItem;
    /**
     * @var Setting
     */
    private $setting;

    public function __construct(MenuItemRepository $menuItem, Setting $setting)
    {
        $this->menuItem = $menuItem;
        $this->setting = $setting;
    }

    public function handle(MenuWasCreated $event)
    {
        $data = [
            'menu_id' => $event->menu->id,
            'position' => 0,
            'is_root' => true,
        ];

        foreach ($this->getEnabledLocales() as $locale) {
            $data[$locale]['title'] = 'root';
        }

        $this->menuItem->create($data);
    }

    /**
     * Return an array of enabled locales
     * @return array
     */
    private function getEnabledLocales()
    {
        return json_decode($this->setting->get('core::locales', '{"en"}'));
    }
}
