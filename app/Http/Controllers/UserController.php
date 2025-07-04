<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateBestScoreRequest;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Users', [
            'users' => User::paginate() 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::create($request->validated());
        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function updateBestScore(UpdateBestScoreRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $newScore = $request->validated()['best_score'];

        if ($newScore > $user->best_score) {
            $user->best_score = $newScore;
            $user->save();
        }
        
        return response()->json(['success' => true, 'best_score' => $user->best_score]);
    }
}
