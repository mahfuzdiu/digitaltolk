<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslationStoreRequest;
use App\Models\Translation;
use Illuminate\Http\Response;

class TranslationController extends Controller
{
    public function update(TranslationStoreRequest $request, $translationId)
    {
        $translation = Translation::findOrFail($translationId);
        $translation->update($request->validated());
        return $translation;
    }

    /**
     * @param $translationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($translationId)
    {
        $translation = Translation::findOrFail($translationId);
        return \response()->json($translation);
    }

    /**
     * @param TranslationStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TranslationStoreRequest $request)
    {
        Translation::create($request->validated());
        return response()->json(['message' => 'Translation added successfully'], Response::HTTP_CREATED);
    }
}
