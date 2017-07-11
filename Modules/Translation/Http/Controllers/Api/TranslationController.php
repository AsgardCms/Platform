<?php

namespace Modules\Translation\Http\Controllers\Api;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Translation\Repositories\TranslationRepository;
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

    public function revisions(Request $request)
    {
        $translation = $this->translation->findTranslationByKey($request->get('key'));
        $translation = $translation->translate($request->get('locale'));

        if (null === $translation) {
            return response()->json(['<tr><td>' . trans('translation::translations.No Revisions yet') . '</td></tr>']);
        }

        return response()->json($this->formatRevisionHistory($translation->revisionHistory));
    }

    private function formatRevisionHistory(Collection $revisionHistory)
    {
        $formattedHistory = [];

        foreach ($revisionHistory as $history) {
            if ($history->key == 'created_at' && !$history->old_value) {
                $formattedHistory[] = $this->getCreatedRevisionTemplate($history);
            } else {
                $formattedHistory[] = $this->getRevisionTemplate($history);
            }
        }

        return array_reverse($formattedHistory);
    }

    private function getRevisionTemplate($history)
    {
        $timeAgo = $history->created_at->diffForHumans();
        $revertRoute = route('admin.translation.translation.update', [$history->revisionable_id, 'oldValue' => $history->oldValue()]);
        $edited = trans('translation::translations.edited');
        $firstName = $history->userResponsible() ? $history->userResponsible()->first_name : '';
        $lastName = $history->userResponsible() ? $history->userResponsible()->last_name : '';

        return <<<HTML
<tr>
    <td>{$history->oldValue()}</td>
    <td>{$firstName} {$lastName}</td>
    <td>$edited</td>
    <td><a data-toggle="tooltip" title="{$history->created_at}">{$timeAgo}</a></td>
    <td><a href="{$revertRoute}"><i class="fa fa-history"></i></a></td>
</tr>
HTML;
    }

    private function getCreatedRevisionTemplate($history)
    {
        $timeAgo = $history->created_at->diffForHumans();
        $created = trans('translation::translations.created');
        $firstName = $history->userResponsible() ? $history->userResponsible()->first_name : '';
        $lastName = $history->userResponsible() ? $history->userResponsible()->last_name : '';

        return <<<HTML
<tr>
    <td></td>
    <td>{$firstName} {$lastName}</td>
    <td>$created</td>
    <td><a data-toggle="tooltip" title="{$history->created_at}">{$timeAgo}</a></td>
    <td></td>
</tr>
HTML;
    }
}
