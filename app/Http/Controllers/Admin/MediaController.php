<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.media.index', compact('media'));
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('media', 'public');

            Media::create([
                'name' => $file->getClientOriginalName(),
                'file_name' => basename($path),
                'mime_type' => $file->getMimeType(),
                'path' => $path,
                'size' => $file->getSize(),
                'disk' => 'public',
            ]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'Files uploaded successfully');
    }

    public function destroy(Media $media)
    {
        try {
            // Delete the database record first
            $media->delete();

            // Then try to delete the file if it exists
            try {
                if ($media->path) {
                    Storage::disk($media->disk)->delete($media->path);
                }
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                Log::error('Failed to delete file: ' . $e->getMessage());
            }

            return redirect()->route('admin.media.index')
                ->with('success', 'File deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.media.index')
                ->with('error', 'Error deleting file: ' . $e->getMessage());
        }
    }

    // Add bulk delete method
    public function bulkDestroy(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            $mediaItems = Media::whereIn('id', $ids)->get();

            foreach ($mediaItems as $media) {
                // Delete the database record first
                $media->delete();

                // Then try to delete the file
                try {
                    if ($media->path) {
                        Storage::disk($media->disk)->delete($media->path);
                    }
                } catch (\Exception $e) {
                    // Log the error but continue with other deletions
                    Log::error('Failed to delete file: ' . $e->getMessage());
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting files: ' . $e->getMessage()
            ]);
        }
    }

    public function list(Request $request)
    {
        $query = Media::query();

        // Apply type filter
        if ($request->type && $request->type !== 'all') {
            $query->where('mime_type', 'like', $request->type . '/%');
        }

        // Apply search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('mime_type', 'like', '%' . $request->search . '%');
            });
        }

        $media = $query->latest()->get()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'path' => $item->path,
                'url' => Storage::url($item->path),
                'type' => explode('/', $item->mime_type)[0],
                'size' => number_format($item->size / 1024, 2) . ' KB',
                'date' => $item->created_at->format('M d, Y')
            ];
        });

        return response()->json($media);
    }
}
