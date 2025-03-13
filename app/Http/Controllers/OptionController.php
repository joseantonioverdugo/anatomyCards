<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOptionRequest;
use App\Models\Flashcard;
use Illuminate\Http\Request;
use App\Models\Option;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOptionRequest $request)
    {
        $validatedData = $request->validated();

        $option = Option::create($validatedData);
        
        $flashcard = Flashcard::with(['options' => function($query) {
            $query->orderBy('option_number');
        }, 'category', 'subcategory'])
        ->find($validatedData['flashcard_id']);
        
        $flashcardArray = $flashcard->toArray();
        
        return back()->with([
            'message' => 'Option created successfully.',
            'option' => $option->toArray(),
            'flashcard' => $flashcardArray,
            'updatedFlashcard' => $flashcardArray
        ]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
