<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Setting\Repositories\SettingRepository;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * @var SettingRepository
     */
    private $setting;

    public function __construct(SettingRepository $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data = [
            'core::template' => 'Flatly',
            'core::locales' => ['en'],
        ];

        $this->setting->createOrUpdate($data);
    }
}
