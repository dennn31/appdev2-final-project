<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Journal::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|exists:entries,id',
            'mood' => 'nullable|in:happy,sad,angry,anxious,proud',
            'content' => 'required|string',
            'image' => 'nullable|image|max:1024',
        ]);
    
        // Check if an Entry already has a Journal
        $existingJournal = Journal::where('entry_id', $request->entry_id)->first();
        if ($existingJournal) {
            return response()->json(['message' => 'This entry already has a journal.'], 400);
        }
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }
    
        $journal = Journal::create([
            'entry_id' => $request->entry_id,
            'mood' => $request->mood,
            'content' => $request->content,
            'image' => $imagePath,
        ]);
    
        return response()->json(['message' => 'Journal entry created successfully', 'journal' => $journal], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Journal::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $journal = Journal::findOrFail($id);

        $request->validate([
            'mood' => 'nullable|in:happy,sad,angry,anxious,proud',
            'content' => 'required|string',
            'image' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('image')) {
            $journal->image = $request->file('image')->store('images', 'public');
        }

        $journal->update($request->all());

        return response()->json(['message' => 'Journal entry updated successfully', 'journal' => $journal]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Journal::destroy($id);
    }

    public function search($name)
    {
        return Journal::where('content', 'like', '%'.$name.'%')
                    ->get();
    }
}
