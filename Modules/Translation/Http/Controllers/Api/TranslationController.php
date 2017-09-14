<?php

namespace Modules\Translation\Http\Controllers\Api;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Translation\Repositories\TranslationRepository;
use Modules\Translation\Services\TranslationRevisions;
use Modules\User\Traits\CanFindUserWithBearerToken;

class TranslationController extends Controller
{
    use CanFindUserWithBearerToken;
    /**
     * @var TranslationRepository
     */
    private $translation;

    public function __construct(TranslationRepository $translation)
    {
        $this->translation = $translation;
    }

    public function update(Request $request)
    {
        Sentinel::login($this->findUserWithBearerToken($request->header('Authorization')));

        $this->translation->saveTranslationForLocaleAndKey(
            $request->get('locale'),
            $request->get('key'),
            $request->get('value')
        );
    }

    public function clearCache()
    {
        $this->translation->clearCache();
    }

    public function revisions(TranslationRevisions $revisions, Request $request)
    {
        return $revisions->get(
            $request->get('key'),
            $request->get('locale')
        );
    }
}
