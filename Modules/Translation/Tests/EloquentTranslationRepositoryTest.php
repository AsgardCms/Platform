<?php

namespace Modules\Translation\Tests;

use Modules\Translation\Repositories\TranslationRepository;

class EloquentTranslationRepositoryTest extends BaseTranslationTest
{
    /**
     * @var TranslationRepository
     */
    private $translation;

    public function setUp()
    {
        parent::setUp();
        $this->translation = app(TranslationRepository::class);
    }

    /** @test */
    public function it_is_true()
    {
        $this->assertTrue(true);
    }
}
