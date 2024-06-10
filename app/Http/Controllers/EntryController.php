<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Entry::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required'
        ]);
          
        $entry = Entry::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
        ]);

        return response()->json(['message' => 'Entry created successfully', 'entry' => $entry], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Entry::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $entry = Entry::find($id);
        $entry->update($request->all());
        return $entry;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $entry = Entry::findOrFail($id);
        $entry->journals()->delete(); 
        $entry->delete(); 
        return response()->json(['message' => 'Entry and associated journals deleted successfully']);
    }

    public function search($name)
    {
        return Entry::where('title', 'like', '%'.$name.'%')
                    ->get();
    }
}
