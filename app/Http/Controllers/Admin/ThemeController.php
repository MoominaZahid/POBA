<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteTheme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $settings = SiteTheme::getAll();

        $googleFonts = [
            'Poppins','Roboto','Open Sans','Lato','Montserrat','Raleway',
            'Nunito','Inter','Playfair Display','Merriweather','Source Sans 3',
            'PT Sans','Noto Sans','Ubuntu','Oswald','Mukta','Work Sans',
            'Quicksand','Josefin Sans','Cabin','Barlow','DM Sans','Karla',
            'Jost','Outfit','Space Grotesk','Sora','Figtree',
            'Plus Jakarta Sans','Lexend','Georgia','Verdana','Trebuchet MS',
        ];

        return view('admin.theme.index', compact('settings', 'googleFonts'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color'   => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'heading_font'    => 'required|string|max:100',
            'heading_size'    => 'required|string|max:20',
            'subheading_size' => 'required|string|max:20',
            'body_size'       => 'required|string|max:20',
        ]);

        SiteTheme::saveAll($request->only([
            'primary_color','secondary_color',
            'heading_font','body_font','nav_font',
            'heading_size','subheading_size','body_size','nav_size',
            'heading_weight','text_color','bg_color','nav_bg_color',
            'footer_bg_color','card_bg_color','border_radius','button_radius',
        ]));

        return back()->with('success', 'Theme settings saved successfully. Changes are live on the website.');
    }

    public function reset()
    {
        SiteTheme::saveAll([
            'primary_color'   => '#1a7a7a',
            'secondary_color' => '#e87722',
            'heading_font'    => 'Poppins',
            'body_font'       => 'Roboto',
            'nav_font'        => 'Poppins',
            'heading_size'    => '32px',
            'subheading_size' => '24px',
            'body_size'       => '14px',
            'nav_size'        => '14px',
            'heading_weight'  => '700',
            'text_color'      => '#2c3e50',
            'bg_color'        => '#ffffff',
            'nav_bg_color'    => '#ffffff',
            'footer_bg_color' => '#1a7a7a',
            'card_bg_color'   => '#ffffff',
            'border_radius'   => '12px',
            'button_radius'   => '30px',
        ]);

        return back()->with('success', 'Theme reset to default POBA settings.');
    }
}
