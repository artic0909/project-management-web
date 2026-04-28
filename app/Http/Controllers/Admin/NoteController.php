<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function index()
    {
        $notes = AdminNote::with('createdBy')->latest()->paginate(10);
        return view('admin.notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,txt,zip,xlsx|max:10240',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('admin_notes', $filename, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType()
                ];
            }
        }

        AdminNote::create([
            'title' => $request->title,
            'content' => $request->content,
            'attachments' => $attachments,
            'created_by' => Auth::id(),
            'created_by_type' => get_class(Auth::user()),
        ]);

        return redirect()->back()->with('success', 'Note added successfully!');
    }

    public function destroy($id)
    {
        $note = AdminNote::findOrFail($id);
        
        if (!empty($note->attachments)) {
            foreach ($note->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }
        
        $note->delete();
        return redirect()->back()->with('success', 'Note deleted successfully!');
    }
}
