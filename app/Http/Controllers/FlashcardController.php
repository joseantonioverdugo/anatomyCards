<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFlashcardRequest;
use App\Http\Requests\UpdateFlashcardRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Flashcard;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Resources\FlashcardResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubcategoryResource;

class FlashcardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashcards = Flashcard::with('category', 'subcategory')->paginate();

        return Inertia::render('Flashcards', [
            'flashcards' => FlashcardResource::collection($flashcards),
            'categories' => CategoryResource::collection(Category::all()),
            'subcategories' => SubcategoryResource::collection(Subcategory::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFlashcardRequest $request)
    {
        $validatedData = $request->validated();
        
        $validatedData['public_id'] = 'temp_' . Str::random(10);
        $validatedData['url'] = 'https://via.placeholder.com/300x200?text=Temp+Image';
        
        $flashcard = Flashcard::create($validatedData);
        
        return redirect()->route('flashcards.index')->with('success', 'Flashcard created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFlashcardRequest $request, string $id)
    {
        $flashcard = FLashcard::findOrFail($id);

        $flashcard->update($request->validated());
        return redirect()->route('flashcards.index')->with('success', 'Flashcard updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flashcard = Flashcard::findOrFail($id);
        $flashcard->delete();
        return redirect()->route('flashcards.index')->with('success', 'Flashcard deleted successfully.');
    }
}
