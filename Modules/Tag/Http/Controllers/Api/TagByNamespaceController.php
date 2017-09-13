<?php

namespace Modules\Tag\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Tag\Repositories\TagRepository;
use Modules\Tag\Transformers\TagTransformer;

class TagByNamespaceController extends Controller
{
    /**
     * @var TagRepository
     */
    private $tag;

    public function __construct(TagRepository $tag)
    {
        $this->tag = $tag;
    }

    public function __invoke(Request $request)
    {
        $availableTags = $this->tag->allForNamespace($request->get('namespace'));

        return response()->json(TagTransformer::collection($availableTags));
    }
}
