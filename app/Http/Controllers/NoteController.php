<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Validate user permission
            if (!Gate::allows('read_note')) {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }

            $user = $request->user();
            $notes = null;

            switch ($user->role) {
                case 'admin':
                    $notes = Note::paginate(10);
                    break;

                case 'editor':
                    $notes = Note::where("user_id", $user->id)->paginate(10);
                    break;
            }

            return response()->json($notes);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        try {
            if (!Gate::allows('create_note')) {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }

            // Store note
            $note = new Note;
            $note->user_id = $request->user()->id;
            $note->title = $request->title;
            $note->content = $request->content;
            $note->save();

            return response()->json([
                "message" => "success",
                "data" => $note,
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        try {
            if (!Gate::allows('read_note')) {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }

            $note = Note::findOrFail($id);

            // Validate user can show this note
            $this->authorize('show', $note);

            return response()->json([
                'message' => 'success',
                'data' => $note,
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, UpdateNoteRequest $request)
    {
        try {
            if (!Gate::allows('update_note')) {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }

            $note = Note::findOrFail($id);

            // Validate user can update this note
            $user = $request->user();
            $this->authorize('update', $note);

            // Update note
            $note->title = $request->title;
            $note->content = $request->content;
            $note->save();

            return response()->json([
                'message' => 'success',
                'data' => $note,
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            if (!Gate::allows('delete_note')) {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }

            $note = Note::findOrFail($id);

            // Validate user can delete this note
            $this->authorize('delete', $note);

            // Delete note
            $note->delete();

            return response()->json([
                'message' => 'success',
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}
