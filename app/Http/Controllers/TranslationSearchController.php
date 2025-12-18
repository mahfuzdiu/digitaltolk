<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslationSearchRequest;
use App\Models\Translation;

class TranslationSearchController extends Controller
{
    /**
     * @param TranslationSearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(TranslationSearchRequest $request)
    {
        $translations = Translation::query()
            ->when($request->locale, fn($q, $locale) => $q->byLocale($locale))
            ->when($request->tag, fn($q, $tags) => $q->byTags($tags))
            ->when($request->content, fn($q, $search) => $q->byContent($search))
            ->when($request->key, fn($q, $key) => $q->byKey($key))
            ->paginate(25);

        return response()->json($translations);
    }
}
