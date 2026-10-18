<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\GalleryFolder;
use App\Support\PublicCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $isMember = Auth::guard('alumni')->check();

        $build = function () use ($request, $isMember) {
            $query = GalleryFolder::query();
            if (! $isMember) {
                $query->where('type', '!=', 'Private');
            }
            if ($request->search)     $query->where('folder_name', 'like', "%{$request->search}%");
            if ($request->class_year) $query->where('class_year', $request->class_year);
            if ($request->event_type) $query->where('type', $request->event_type);
            return $query->orderByDesc('created_at')->paginate(8);
        };

        $filtered = array_filter($request->only(['search', 'class_year', 'event_type']));
        $page     = max(1, (int) $request->query('page', 1));
        $audience = $isMember ? 'member' : 'guest';

        $folders = empty($filtered)
            ? PublicCache::remember("gallery.index.{$audience}.p{$page}", $build)
            : $build();

        return view('customer.gallery.index', compact('folders'));
    }

    public function show($id)
    {
        $folder = PublicCache::remember('gallery.show.' . $id, fn () =>
            GalleryFolder::with('images')
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
                ->firstOrFail());

        if ($folder->type === 'Private' && ! Auth::guard('alumni')->check()) {
            return redirect()->route('login')
                ->with('error', 'This gallery folder is only accessible to members. Please log in.');
        }

        return view('customer.gallery.show', compact('folder'));
    }
}
