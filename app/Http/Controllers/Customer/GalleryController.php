<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\GalleryFolder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryFolder::query();
        if (! Auth::guard('alumni')->check()) {
            $query->where('type', '!=', 'Private');
        }
        if ($request->search)     $query->where('folder_name', 'like', "%{$request->search}%");
        if ($request->class_year) $query->where('class_year', $request->class_year);
        if ($request->event_type) $query->where('type', $request->event_type);
        $folders = $query->orderByDesc('created_at')->paginate(8);
        return view('customer.gallery.index', compact('folders'));
    }

    public function show($id)
    {
        $folder = GalleryFolder::with('images')
            ->where(function ($q) use ($id) {
                if (is_numeric($id)) {
                    $q->where('id', (int) $id)
                      ->orWhere('slug', $id)
                      ->orWhere('folder_name', $id);
                } else {
                    $q->where('slug', $id)
                      ->orWhere('slug', Str::slug($id))
                      ->orWhere('folder_name', $id);
                }
            })
            ->firstOrFail();

        if ($folder->type === 'Private' && ! Auth::guard('alumni')->check()) {
            return redirect()->route('login')
                ->with('error', 'This gallery folder is only accessible to members. Please log in.');
        }

        return view('customer.gallery.show', compact('folder'));
    }
}
