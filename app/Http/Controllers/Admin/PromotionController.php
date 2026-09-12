<?php
// FILE: app/Http/Controllers/Admin/PromotionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index()
    {
        $promos = Promotion::orderByDesc('created_at')->paginate(10);
        return view('admin.cms.promotions', compact('promos'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);

        $data = $request->except(['_token', 'image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promotions');
        }

        Promotion::create($data);

        return back()->with('success', 'Promotion added successfully.');
    }

    public function destroy($id)
    {
        $promo = Promotion::findOrFail($id);

        if ($promo->image) {
            Storage::delete($promo->image);
        }

        $promo->delete();

        return back()->with('success', 'Promotion deleted.');
    }
}
