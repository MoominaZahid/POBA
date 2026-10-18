<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\AlumniUser;
use App\Models\CmsSetting;
use App\Models\Promotion;

use App\Models\Committee;
use App\Support\PublicCache;

class HomeController extends Controller {
    public function index() {
        $news       = PublicCache::remember('home.news', fn () =>
            News::orderByRaw("COALESCE(published_at, created_at) DESC")->take(4)->get());
        $starAlumni = PublicCache::remember('home.star_alumni', fn () =>
            AlumniUser::where('is_star_alumni', true)->where('status', 'approved')->take(5)->get());
        $settings   = PublicCache::remember('cms.settings', fn () => CmsSetting::pluck('value', 'key'));
        return view('customer.home', compact('news', 'starAlumni', 'settings'));
    }

    public function about() {
        $settings = PublicCache::remember('cms.settings', fn () => CmsSetting::pluck('value', 'key'));
        $timeline = json_decode($settings['history_timeline'] ?? '[]', true);
        $executive = PublicCache::remember('about.executive', fn () =>
            Committee::where('type', 'executive')->whereNull('parent_id')->first()
                  ?? Committee::where('type', 'executive')->first());
        $workingMain = PublicCache::remember('committees.working_main', fn () =>
            Committee::where('type', 'working')->whereNull('parent_id')->first());
        return view('customer.about', compact('settings', 'timeline', 'executive', 'workingMain'));
    }

    public function verticals() {
        $executive = PublicCache::remember('verticals.executive', fn () =>
            Committee::with('members')->where('type', 'executive')->whereNull('parent_id')->first()
                  ?? Committee::with('members')->where('type', 'executive')->first());
        $workingMain = PublicCache::remember('committees.working_main', fn () =>
            Committee::where('type', 'working')->whereNull('parent_id')->first());
        $working = PublicCache::remember('verticals.working.' . ($workingMain->id ?? 'none'), fn () =>
            Committee::with('members')
                ->where(function($q) use ($workingMain) {
                    if ($workingMain) {
                        $q->where('parent_id', $workingMain->id);
                    } else {
                        $q->where('type', 'working')->where('id', '!=', 1);
                    }
                })
                ->orderBy('id')
                ->get());
        return view('customer.verticals.index', compact('executive', 'workingMain', 'working'));
    }

    public function verticalsExecutive() {
        $executive = PublicCache::remember('verticals.executive', fn () =>
            Committee::with('members')->where('type', 'executive')->whereNull('parent_id')->first()
                  ?? Committee::with('members')->where('type', 'executive')->first());
        return view('customer.verticals.executive', compact('executive'));
    }

    public function verticalsWorking() {
        $workingMain = PublicCache::remember('committees.working_main', fn () =>
            Committee::where('type', 'working')->whereNull('parent_id')->first());
        $working = PublicCache::remember('verticals.working.' . ($workingMain->id ?? 'none'), fn () =>
            Committee::with('members')
                ->where(function($q) use ($workingMain) {
                    if ($workingMain) {
                        $q->where('parent_id', $workingMain->id);
                    } else {
                        $q->where('type', 'working')->where('id', '!=', 1);
                    }
                })
                ->orderBy('id')
                ->get());
        return view('customer.verticals.working', compact('workingMain', 'working'));
    }

    public function promotions() {
        $promos = PublicCache::remember('promotions.' . today()->toDateString(), fn () =>
            Promotion::where(function($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', today());
            })->orderByDesc('created_at')->get());
        return view('customer.promotions', compact('promos'));
    }

    public function privacyPolicy() {
        $settings = PublicCache::remember('cms.settings', fn () => CmsSetting::pluck('value', 'key'));
        return view('customer.privacy', compact('settings'));
    }

    public function termsConditions() {
        $settings = PublicCache::remember('cms.settings', fn () => CmsSetting::pluck('value', 'key'));
        return view('customer.terms', compact('settings'));
    }
}
