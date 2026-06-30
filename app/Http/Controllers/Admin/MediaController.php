<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaAsset::latest();

        if ($folder = $request->input('folder')) {
            $query->where('folder', $folder);
        }

        $mediaAssets = $query->paginate(24)->withQueryString();
        $folders = MediaAsset::select('folder')->distinct()->whereNotNull('folder')->orderBy('folder')->pluck('folder');

        return view('admin.media.index', compact('mediaAssets', 'folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files'   => 'nullable|array',
            'files.*' => 'required|image|max:10240',
            'file'    => 'nullable|file|max:20480',
        ]);

        $uploaded = 0;
        $year  = now()->format('Y');
        $month = now()->format('m');

        $files = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $f) {
                $files[] = $f;
            }
        }

        if ($request->hasFile('file')) {
            $files[] = $request->file('file');
        }

        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();
            $safeName     = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $path         = Storage::disk('public')->putFileAs("media/{$year}/{$month}", $file, $safeName);

            MediaAsset::create([
                'disk'        => 'public',
                'path'        => $path,
                'filename'    => $originalName,
                'mime_type'   => $file->getMimeType(),
                'size'        => $file->getSize(),
                'alt_text'    => $request->input('alt_text'),
                'folder'      => $request->input('folder', 'general'),
                'uploaded_by' => auth()->id(),
            ]);

            $uploaded++;
        }

        $message = "{$uploaded} file(s) uploaded successfully.";

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(MediaAsset $mediaAsset)
    {
        Storage::disk($mediaAsset->disk)->delete($mediaAsset->path);
        $mediaAsset->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }

    public function updateAlt(Request $request, MediaAsset $mediaAsset)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
        ]);

        $mediaAsset->update(['alt_text' => $request->input('alt_text')]);

        return response()->json(['success' => true]);
    }
}
