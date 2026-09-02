<?php
// FILE: app/Http/Controllers/Admin/GalleryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryFolder;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request) {
        $query = GalleryFolder::query();
        if ($request->search) {
            $query->where('folder_name','like',"%{$request->search}%");
        }
        $folders = $query->orderByDesc('created_at')->paginate(10);
        return view('admin.gallery.index', compact('folders'));
    }

    public function create() {
        return view('admin.gallery.create');
    }

    public function store(Request $request) {
        $request->validate(['folder_name'=>'required','class_year'=>'required','type'=>'required']);
        $data = $request->except('_token');
        $data['slug'] = Str::slug($request->folder_name);
        GalleryFolder::create($data);
        return redirect()->route('admin.gallery.index')->with('success','Folder created.');
    }

    public function edit($id) {
        $folder = GalleryFolder::findOrFail($id);
        return view('admin.gallery.edit', compact('folder'));
    }

    public function update(Request $request, $id) {
        $folder = GalleryFolder::findOrFail($id);
        $data   = $request->except(['_token','_method']);
        if ($request->folder_name) {
            $data['slug'] = Str::slug($request->folder_name);
        }
        $folder->update($data);
        return redirect()->route('admin.gallery.index')->with('success','Folder updated.');
    }

    public function destroy($id) {
        $folder = GalleryFolder::with('images')->findOrFail($id);
        foreach ($folder->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $folder->delete();
        return back()->with('success','Folder deleted.');
    }

    public function images($id) {
        $folder = GalleryFolder::with('images')->findOrFail($id);
        return view('admin.gallery.images', compact('folder'));
    }

    public function addImages(Request $request, $id) {
        $folder = GalleryFolder::findOrFail($id);
        $files  = $request->file('images', []);

        if (empty($files)) {
            return back()->with('error', 'Please select at least one image to upload.');
        }

        $oversized = [];
        $invalidType = [];
        foreach ($files as $file) {
            if ($file->getSize() > 5120 * 1024) {
                $oversized[] = '"' . $file->getClientOriginalName() . '" (' . round($file->getSize() / (1024 * 1024), 2) . ' MB)';
            }
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $invalidType[] = '"' . $file->getClientOriginalName() . '"';
            }
        }

        if (!empty($oversized) || !empty($invalidType)) {
            $msgs = [];
            if (!empty($oversized)) {
                $msgs[] = 'The following file(s) exceed the 5MB limit: ' . implode(', ', $oversized);
            }
            if (!empty($invalidType)) {
                $msgs[] = 'The following file(s) have unsupported formats: ' . implode(', ', $invalidType);
            }
            return back()->with('error', 'Upload failed: ' . implode(' | ', $msgs));
        }

        $order = $folder->images()->max('sort_order') ?? 0;
        foreach ($files as $file) {
            $path = $file->store('gallery', 'public');
            GalleryImage::create(['gallery_folder_id' => $id, 'image_path' => $path, 'sort_order' => ++$order]);
        }

        return back()->with('success', count($files) . ' image(s) uploaded successfully.');
    }

    public function deleteImage($imageId) {
        $image = GalleryImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return back()->with('success','Image deleted.');
    }
}
