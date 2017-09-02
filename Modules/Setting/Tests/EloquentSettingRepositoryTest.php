<?php

namespace Modules\Setting\Tests;

use Illuminate\Support\Facades\Event;
use Modules\Setting\Events\SettingIsCreating;
use Modules\Setting\Events\SettingIsUpdating;
use Modules\Setting\Events\SettingWasCreated;
use Modules\Setting\Events\SettingWasUpdated;

class EloquentSettingRepositoryTest extends BaseSettingTest
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_creates_translated_setting()
    {
        // Prepare
        $data = [
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
        ];

        // Run
        $this->settingRepository->createOrUpdate($data);

        // Assert
        $setting = $this->settingRepository->find(1);
        $this->assertEquals('core::site-name', $setting->name);
        $this->assertEquals('AsgardCMS_en', $setting->translate('en')->value);
        $this->assertEquals('AsgardCMS_fr', $setting->translate('fr')->value);
    }

    /** @test */
    public function it_creates_plain_setting()
    {
        // Prepare
        $data = [
            'core::template' => 'asgard',
        ];

        // Run
        $this->settingRepository->createOrUpdate($data);

        // Assert
        $setting = $this->settingRepository->find(1);
        $this->assertEquals('core::template', $setting->name);
        $this->assertEquals('asgard', $setting->plainValue);
    }

    /** @test */
    public function it_finds_setting_by_name()
    {
        // Prepare
        $data = [
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
        ];

        // Run
        $this->settingRepository->createOrUpdate($data);

        // Assert
        $setting = $this->settingRepository->findByName('core::site-name');
        $this->assertEquals('core::site-name', $setting->name);
        $this->assertEquals('AsgardCMS_en', $setting->translate('en')->value);
        $this->assertEquals('AsgardCMS_fr', $setting->translate('fr')->value);
    }

    /** @test */
    public function it_returns_module_settings()
    {
        // Prepare
        $data = [
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
            'core::template' => 'asgard',
            'blog::posts-per-page' => 10,
        ];

        // Run
        $this->settingRepository->createOrUpdate($data);

        // Assert
        $blogSettings = $this->settingRepository->findByModule('blog');
        $this->assertEquals(1, $blogSettings->count());
        $coreSettings = $this->settingRepository->findByModule('core');
        $this->assertEquals(2, $coreSettings->count());
    }

    /** @test */
    public function it_encodes_array_of_non_translatable_data()
    {
        // Prepare
        $data = [
            'core::locales' => [
                "su",
                "bi",
                "bs",
            ],
        ];

        // Run
        $this->settingRepository->createOrUpdate($data);

        // Assert
        $setting = $this->settingRepository->find(1);
        $this->assertEquals('core::locales', $setting->name);
        $this->assertEquals('["su","bi","bs"]', $setting->plainValue);
    }

    /** @test */
    public function it_triggers_event_when_setting_was_created()
    {
        Event::fake();

        $data = [
            'core::template' => 'asgard',
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
        ];
        $this->settingRepository->createOrUpdate($data);

        Event::assertDispatched(SettingWasCreated::class, function ($e) {
            return $e->setting->name === 'core::template';
        });
    }

    /** @test */
    public function it_triggers_event_when_setting_is_creating()
    {
        Event::fake();

        $data = [
            'core::template' => 'asgard',
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
        ];
        $this->settingRepository->createOrUpdate($data);

        Event::assertDispatched(SettingIsCreating::class, function (SettingIsCreating $e) {
            return $e->getSettingName() === 'core::template' && $e->getSettingValues() === 'asgard';
        });
    }

    /** @test */
    public function it_can_change_data_when_it_is_creating_event()
    {
        Event::listen(SettingIsCreating::class, function (SettingIsCreating $event) {
            if ($event->getSettingName() === 'core::template') {
                $event->setSettingValues('my-template');
            }
            if ($event->getSettingName() === 'core::site-name') {
                $event->setSettingValues([
                    'en' => 'English AsgardCMS',
                ]);
            }
        });

        $data = [
            'core::template' => 'asgard',
            'blog::posts' => 10,
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
        ];
        $this->settingRepository->createOrUpdate($data);

        $this->assertEquals('my-template', $this->settingRepository->findByName('core::template')->plainValue);
        $this->assertEquals(10, $this->settingRepository->findByName('blog::posts')->plainValue);
        $this->assertEquals('English AsgardCMS', $this->settingRepository->findByName('core::site-name')->translate('en')->value);
    }

    /** @test */
    public function it_triggers_event_when_setting_was_updated()
    {
        Event::fake();

        $data = [
            'core::template' => 'asgard',
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
        ];
        $this->settingRepository->createOrUpdate($data);
        $this->settingRepository->createOrUpdate(['core::template' => 'flatly']);

        Event::assertDispatched(SettingWasUpdated::class, function ($e) {
            return $e->setting->name === 'core::template';
        });
    }

    /** @test */
    public function it_triggers_event_when_setting_is_updating()
    {
        Event::fake();

        $data = [
            'core::template' => 'asgard',
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
        ];
        $this->settingRepository->createOrUpdate($data);
        $this->settingRepository->createOrUpdate(['core::template' => 'flatly']);

        Event::assertDispatched(SettingIsUpdating::class, function ($e) {
            return $e->getSetting()->name === 'core::template';
        });
    }

    /** @test */
    public function it_can_change_date_when_updating_setting()
    {
        Event::listen(SettingIsUpdating::class, function (SettingIsUpdating $event) {
            if ($event->getSettingName() === 'core::template') {
                $event->setSettingValues('my-template');
            }
            if ($event->getSettingName() === 'core::site-name') {
                $event->setSettingValues([
                    'en' => 'English AsgardCMS',
                ]);
            }
        });

        $data = [
            'core::template' => 'asgard',
            'blog::posts' => 10,
            'core::site-name' => [
                'en' => 'AsgardCMS_en',
                'fr' => 'AsgardCMS_fr',
            ],
        ];
        $this->settingRepository->createOrUpdate($data);
        $this->settingRepository->createOrUpdate([
            'core::template' => 'flatly',
            'core::site-name' => [
                'en' => 'The AsgardCMS_en',
                'fr' => 'The AsgardCMS_fr',
            ],
        ]);

        $this->assertEquals('my-template', $this->settingRepository->findByName('core::template')->plainValue);
        $this->assertEquals(10, $this->settingRepository->findByName('blog::posts')->plainValue);
        $this->assertEquals('English AsgardCMS', $this->settingRepository->findByName('core::site-name')->translate('en')->value);
    }
}
