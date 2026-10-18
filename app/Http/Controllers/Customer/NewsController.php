<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\News;

use Illuminate\Http\Request;

class NewsController extends Controller {
    public function index(Request $request) {
        $news = News::orderByRaw("COALESCE(published_at, created_at) DESC")->paginate(12);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html'     => view('customer.news._items', compact('news'))->render(),
                'hasMore'  => $news->hasMorePages(),
                'nextPage' => $news->hasMorePages() ? $news->currentPage() + 1 : null,
            ]);
        }

        return view('customer.news.index', compact('news'));
    }

    public function show($id) {
        $item = News::findOrFail($id);
        return view('customer.news.show', compact('item'));
    }
}
