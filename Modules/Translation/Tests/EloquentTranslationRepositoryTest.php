<?php

namespace Modules\Translation\Tests;

use Modules\Translation\Entities\Translation;
use Modules\Translation\Repositories\TranslationRepository;

class EloquentTranslationRepositoryTest extends BaseTranslationTest
{
    /**
     * @var TranslationRepository
     */
    private $translation;

    public function setUp(): void
    {
        parent::setUp();
        $this->translation = app(TranslationRepository::class);
    }

    /** @test */
    public function it_stores_translations_for_locale_and_key()
    {
        $this->translation->saveTranslationForLocaleAndKey('en', 'user::index.show', 'my key');

        $this->assertCount(1, $this->translation->all());
    }

    /** @test */
    public function it_finds_a_translation_value_by_key_and_locale()
    {
        $this->translation->saveTranslationForLocaleAndKey('en', 'user::index.show', 'my key');

        $this->assertEquals('my key', $this->translation->findByKeyAndLocale('user::index.show', 'en'));
    }

    /** @test */
    public function it_returns_empty_string_if_no_db_match_found()
    {
        $this->assertEquals('', $this->translation->findByKeyAndLocale('user::index.show', 'en'));
        $this->assertEquals('', $this->translation->findByKeyAndLocale('user::index.show', 'fr'));
    }

    /** @test */
    public function it_finds_a_translation_by_key()
    {
        $this->translation->saveTranslationForLocaleAndKey('en', 'user::index.show', 'my key');

        $translation = $this->translation->findTranslationByKey('user::index.show');
        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertEquals('user::index.show', $translation->key);
        $this->assertEquals('my key', $translation->translate('en')->value);
    }

    /** @test */
    public function it_formats_all_translation_for_gui()
    {
        $this->translation->saveTranslationForLocaleAndKey('en', 'user::index.show', 'Show user');
        $this->translation->saveTranslationForLocaleAndKey('en', 'user::index.update', 'Update user');
        $this->translation->saveTranslationForLocaleAndKey('fr', 'user::index.show', 'Voir user');
        $this->translation->saveTranslationForLocaleAndKey('fr', 'user::index.update', 'Mettre à jour user');

        $expected = [
            'en' => [
                'user::index.show' => 'Show user',
                'user::index.update' => 'Update user',
            ],
            'fr' => [
                'user::index.show' => 'Voir user',
                'user::index.update' => 'Mettre à jour user',
            ],
        ];

        $this->assertEquals($expected, $this->translation->allFormatted());
    }

    /** @test */
    public function it_gets_all_translations_for_group_namespace_and_locale()
    {
        $this->translation->saveTranslationForLocaleAndKey('en', 'user::index.show', 'Show user');
        $this->translation->saveTranslationForLocaleAndKey('en', 'user::index.update', 'Update user');
        $this->translation->saveTranslationForLocaleAndKey('fr', 'user::index.show', 'Voir user');
        $this->translation->saveTranslationForLocaleAndKey('fr', 'user::index.update', 'Mettre à jour user');

        $expected = [
            'show' => 'Show user',
            'update' => 'Update user',
        ];

        $this->assertEquals($expected, $this->translation->getTranslationsForGroupAndNamespace('en', 'index', 'user'));
    }
}
