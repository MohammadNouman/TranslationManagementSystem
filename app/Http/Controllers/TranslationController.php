<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller
{
    /**
     * List all translations.
     */
    public function index(Request $request)
    {
        // \DB::enableQueryLog();
        $page = request('page', 1);
        $perPage = 10;

        $translations = Translation::select(['id', 'locale', 'key', 'value', 'tags'])
            ->orderBy('id')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        // dd(DB::getQueryLog());
        return response()->json([
            'data' => $translations,
            'current_page' => $page,
        ], 200);
    }

    /**
     * Create a new translation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|max:10',
            'key' => 'required|string|unique:translations,key',
            'value' => 'required|string',
            'tags' => 'array',
        ]);

        $translation = Translation::create($validated);
        return response()->json($translation, 201);
    }

    /**
     * Update an existing translation.
     */
    public function update(Request $request, $id)
    {
        $translation = Translation::findOrFail($id);

        $validated = $request->validate([
            'locale' => 'string|max:10',
            'key' => 'string|unique:translations,key,' . $id,
            'value' => 'string',
            'tags' => 'array',
        ]);

        $translation->update($validated);
        return response()->json($translation);
    }

    /**
     * Search translations by key, value, or tags.
     */
    public function search(Request $request)
    {
        $term = $request->query('term');
        $translations = Translation::search($term)->paginate(50);
        return response()->json($translations);
    }

    /**
     * Export translations in JSON format.
     */
    public function export()
    {
        $result = Cache::remember('translations_json', 60, function () {
            return Translation::query()
                ->select(['locale', 'key', 'value']) // Only fetch necessary columns
                ->get()
                ->groupBy('locale')
                ->map(function ($translations) {
                    return $translations->pluck('value', 'key');
                });
        });
    
        return response()->json($result, 200);
    }
}
