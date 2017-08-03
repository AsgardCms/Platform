<?php

namespace Modules\Translation\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Collection;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Modules\User\Traits\CanFindUserWithBearerToken;
use Modules\Translation\Services\TranslationRevisions;
use Modules\Translation\Repositories\TranslationRepository;

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
