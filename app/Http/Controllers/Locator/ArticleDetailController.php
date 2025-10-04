<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locator\ArticleDetail;
use Inertia\Inertia;

class ArticleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $article= ArticleDetail::all();
        return;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'application_form_id' => 'required|integer|exists:application_forms,id',
        'marks_and_number' => 'required|string|max:255',
        'qty' => 'required|integer|min:1',
        'detailed_description_of_article' => 'required|string|max:500',
        'gross_weight' => 'nullable|string|max:255',
    ]);

    $article = ArticleDetail::create([
        'application_form_id' => $validated['application_form_id'],
        'marks_and_number' => $validated['marks_and_number'],
        'qty' => $validated['qty'],
        'detailed_description_of_article' => $validated['detailed_description_of_article'],
        'gross_weight' => $validated['gross_weight'] ?? null,
        'user_id' => auth()->id(),
    ]);

    return response()->json(['article' => $article]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
 public function destroy($id)
{
    $article = ArticleDetail::findOrFail($id);
    $article->delete();

    // Return 204 for AJAX
    return response()->noContent(); 
}
}
