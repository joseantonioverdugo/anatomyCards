<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Subcategories', [
            'subcategories' => Subcategory::paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubcategoryRequest $request)
    {
        $subcategory = Subcategory::create($request->validated());

        return redirect()->route('subcategories.index')->with('success', 'Subcategory created successfully.');
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
    public function update(UpdateSubcategoryRequest $request, string $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update($request->validated());

        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete(); 

        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
