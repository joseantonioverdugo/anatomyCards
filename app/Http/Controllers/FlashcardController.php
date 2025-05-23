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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Resources\FlashcardResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubcategoryResource;
use Exception;

class FlashcardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashcards = Flashcard::with('category', 'subcategory', 'options')->paginate();

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
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            try {
                $path = Storage::disk('s3')->put('flashcards', $file);
                $url = Storage::disk('s3')->url($path);
            } catch (Exception $e) {
                Log::error('Error al subir imagen a S3: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'Error al subir la imagen.']);
            }

            $validatedData['public_id'] = $path;
            $validatedData['url'] = $url;
        }
        
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
        $flashcard = Flashcard::findOrFail($id);
        $validatedData = array_filter($request->validated(), function($value) {
            return $value !== null;
        });
        
        if ($request->hasFile('image')) {
            try {
                if ($flashcard->public_id) {
                    Storage::disk('s3')->delete($flashcard->public_id);
                }
                
                $file = $request->file('image');
                $path = Storage::disk('s3')->put('flashcards', $file);
                $url = Storage::disk('s3')->url($path);
                
                $validatedData['public_id'] = $path;
                $validatedData['url'] = $url;
            } catch (Exception $e) {
                Log::error('Error al actualizar imagen en S3: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'Error al subir la imagen: ' . $e->getMessage()]);
            }
        }
        $flashcard->fill($validatedData)->save();
        return redirect()->route('flashcards.index')->with('success', 'Flashcard updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flashcard = Flashcard::findOrFail($id);
        
        // Eliminar imagen de S3
            Storage::disk('s3')->delete($flashcard->public_id);
        
        $flashcard->delete();
        return redirect()->route('flashcards.index')->with('success', 'Flashcard deleted successfully.');
    }
}
