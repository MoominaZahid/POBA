<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\AlumniUser;
use App\Models\CmsSetting;
use App\Models\Promotion;

use App\Models\Committee;

class HomeController extends Controller {
    public function index() {
        $news       = News::orderByRaw("COALESCE(published_at, created_at) DESC")->take(4)->get();
        $starAlumni = AlumniUser::where('is_star_alumni', true)->where('status', 'approved')->take(5)->get();
        $settings   = CmsSetting::pluck('value', 'key');
        return view('customer.home', compact('news', 'starAlumni', 'settings'));
    }

    public function about() {
        $settings = CmsSetting::pluck('value', 'key');
        $timeline = json_decode($settings['history_timeline'] ?? '[]', true);
        $executive = Committee::where('type', 'executive')->whereNull('parent_id')->first()
                  ?? Committee::where('type', 'executive')->first();
        $workingMain = Committee::where('type', 'working')->whereNull('parent_id')->first();
        return view('customer.about', compact('settings', 'timeline', 'executive', 'workingMain'));
    }

    public function verticals() {
        $executive = Committee::with('members')->where('type', 'executive')->whereNull('parent_id')->first()
                  ?? Committee::with('members')->where('type', 'executive')->first();
        $workingMain = Committee::where('type', 'working')->whereNull('parent_id')->first();
        $working = Committee::with('members')
            ->where(function($q) use ($workingMain) {
                if ($workingMain) {
                    $q->where('parent_id', $workingMain->id);
                } else {
                    $q->where('type', 'working')->where('id', '!=', 1);
                }
            })
            ->orderBy('id')
            ->get();
        return view('customer.verticals.index', compact('executive', 'workingMain', 'working'));
    }

    public function verticalsExecutive() {
        $executive = Committee::with('members')->where('type', 'executive')->whereNull('parent_id')->first()
                  ?? Committee::with('members')->where('type', 'executive')->first();
        return view('customer.verticals.executive', compact('executive'));
    }

    public function verticalsWorking() {
        $workingMain = Committee::where('type', 'working')->whereNull('parent_id')->first();
        $working = Committee::with('members')
            ->where(function($q) use ($workingMain) {
                if ($workingMain) {
                    $q->where('parent_id', $workingMain->id);
                } else {
                    $q->where('type', 'working')->where('id', '!=', 1);
                }
            })
            ->orderBy('id')
            ->get();
        return view('customer.verticals.working', compact('workingMain', 'working'));
    }

    public function promotions() {
        $promos = Promotion::where(function($q) {
            $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', today());
        })->orderByDesc('created_at')->get();
        return view('customer.promotions', compact('promos'));
    }

    public function privacyPolicy() {
        $settings = CmsSetting::pluck('value', 'key');
        return view('customer.privacy', compact('settings'));
    }

    public function termsConditions() {
        $settings = CmsSetting::pluck('value', 'key');
        return view('customer.terms', compact('settings'));
    }
}
