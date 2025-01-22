<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    /**
     * List all translations.
     */
    public function index(Request $request)
    {
        $translations = Translation::paginate(50); // Adjust as needed
        return response()->json($translations);
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
        $translations = Translation::all();
        return response()->json($translations);
    }
}
