<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Flashcard;
use App\Http\Resources\FlashcardResource;

class GameController extends Controller
{
    public function index()
    {
        $flashcards = Flashcard::with(['category', 'subcategory', 'options'])
            ->get();
        return Inertia::render('Game', [
            'user' => auth()->user(),
            'flashcards' => FlashcardResource::collection($flashcards),
        ]);
    }
}
