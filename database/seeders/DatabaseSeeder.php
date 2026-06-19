<?php
// FILE: database/seeders/DatabaseSeeder.php
// REPLACE entire file

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AlumniUser;
use App\Models\CmsSetting;
use App\Models\Faq;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ───────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@poba.com'],
            [
                'name'        => 'Super Admin',
                'gender'      => 'Male',
                'role'        => 'superadmin',   // Full access — no permissions array needed
                'permissions' => null,
                'password'    => Hash::make('password'),
            ]
        );

        // ── Sample Limited Admin (News + Gallery only) ────────────────────────
        User::firstOrCreate(
            ['email' => 'editor@poba.com'],
            [
                'name'        => 'News Editor',
                'gender'      => 'Male',
                'role'        => 'admin',
                'permissions' => ['news', 'gallery'],   // Only these two sections
                'password'    => Hash::make('password'),
            ]
        );

        // ── Sample Limited Admin (Events only) ────────────────────────────────
        User::firstOrCreate(
            ['email' => 'eventmanager@poba.com'],
            [
                'name'        => 'Event Manager',
                'gender'      => 'Female',
                'role'        => 'admin',
                'permissions' => ['events'],
                'password'    => Hash::make('password'),
            ]
        );

        // ── Approved Alumni ───────────────────────────────────────────────────
       // ── ALUMNI USERS (Exact data from local DB) ──────────────────────────────
AlumniUser::updateOrCreate(
    ['email' => 'alumni@poba.com'],
    [
        'full_name'            => 'Muhammad Zakaullah',
        'password'             => Hash::make('password'), // Replace with actual hash if needed
        'ccp_no'               => 228,
        'house'                => 'Jinnah',
        'education'            => 'Bachelors',
        'field_of_study'       => 'BBA',
        'field_of_work'        => 'Marketing',
        'current_city'         => 'Lahore',
        'current_country'      => 'Pakistan',
        'current_designation'  => 'HOD Marketing Department',
        'current_organization' => 'Adsells',
        'phone_number'         => '+92 345 450 1450',
        'achievements'         => null,
        'profile_photo'        => null,
        'cnic_file'            => null,
        'consent_sharing'      => 1,
        'agree_terms'          => 1,
        'privacy_settings'     => null,
        'status'               => 'approved',
        'is_active'            => 1,
        'is_star_alumni'       => 0,
        'star_description'     => null,
        'featured_text'        => null,
        'class_year'           => 1975,
        'remember_token'       => null,
        'created_at'           => '2026-06-17 20:27:03',
        'updated_at'           => '2026-06-17 20:27:03',
    ]
);

AlumniUser::updateOrCreate(
    ['email' => 'amna12@gmail.com'],
    [
        'full_name'            => 'ayesha',
        'password'             => '$2y$12$HY3XlZgiUIkQw.QRIVnmPOEwMLxYpnOadVCtwgO6z/KnZE2Vs4wBS', // ⚠️ PASTE FULL HASH
        'entry'                => 18,
        'ccp_no'               => 7865,
        'house'                => 'Ayub',
        'education'            => 'Bachelors',
        'field_of_study'       => 'cs',
        'field_of_work'        => 'abc',
        'current_city'         => 'Islamabad',
        'current_country'      => 'Pakistan',
        'current_designation'  => 'abc',
        'current_organization' => 'test',
        'phone_number'         => '98658778654',
        'achievements'         => 'bbbb',
        'profile_photo'        => 'profiles/BuOKOv5xqgSXOihVUlbbebRVSDV7Wia50UHEOHth....',
        'cnic_file'            => 'cnics/g5l50lcEMC6M4jyM7KnGP2aiQOXXvX0nAn17A2yS.png',
        'consent_sharing'      => 1,
        'agree_terms'          => 1,
        'privacy_settings'     => null,
        'status'               => 'pending',
        'is_active'            => 0,
        'is_star_alumni'       => 0,
        'star_description'     => null,
        'featured_text'        => null,
        'class_year'           => 2009,
        'remember_token'       => null,
        'created_at'           => '2026-06-17 20:29:43',
        'updated_at'           => '2026-06-17 20:29:43',
    ]
);

AlumniUser::updateOrCreate(
    ['email' => 'moominazahid44@gmail.com'],
    [
        'full_name'            => 'moomina zahid',
        'password'             => '$2y$12$xzV.6mNeS8ChLkc9xejXXueCltHQHckfP6b.DMXdIakYmGi/Uy4LS', // ⚠️ PASTE FULL HASH
        'entry'                => 18,
        'ccp_no'               => 876,
        'house'                => 'Jinnah',
        'education'            => 'Bachelors',
        'field_of_study'       => 'SE',
        'field_of_work'        => 'dev',
        'current_city'         => 'Islamabad',
        'current_country'      => 'Pakistan',
        'current_designation'  => 'dev',
        'current_organization' => 'xyz',
        'phone_number'         => '+92341169668',
        'achievements'         => 'abc',
        'profile_photo'        => 'profiles/profile_3_1781731908.jpg',
        'cnic_file'            => 'cnics/cnic_3_1781731908.jpg',
        'consent_sharing'      => 1,
        'agree_terms'          => 1,
        'privacy_settings'     => json_encode(['email']),
        'status'               => 'approved',
        'is_active'            => 1,
        'is_star_alumni'       => 1,
        'star_description'     => 'testing',
        'featured_text'        => null,
        'class_year'           => 2010,
        'remember_token'       => '5DbALlGsTLdDLfWLiHojEnITSOUzfgunWAdxDsKyh0LEdcIx7k...',
        'created_at'           => '2026-06-17 21:31:48',
        'updated_at'           => '2026-06-18 16:24:33',
    ]
);

AlumniUser::updateOrCreate(
    ['email' => 'moominazahid446@gmail.com'],
    [
        'full_name'            => 'moomina',
        'password'             => '$2y$12$Z1z2vruuBSPRDJVPBo9CyeILPkOk4a5e5BkV9d8HVZ2Nod.ZP7BcW', // ⚠️ PASTE FULL HASH
        'entry'                => 11,
        'ccp_no'               => 1364,
        'house'                => 'Jinnah',
        'education'            => 'Bachelors',
        'field_of_study'       => 'SE',
        'field_of_work'        => 'DEV',
        'current_city'         => 'ISB',
        'current_country'      => 'pakistan',
        'current_designation'  => 'DEV',
        'current_organization' => 'dev',
        'phone_number'         => '97659899',
        'achievements'         => 'ndwid',
        'profile_photo'        => 'profiles/profile_4_1781786699.png',
        'cnic_file'            => 'cnics/cnic_4_1781786701.png',
        'consent_sharing'      => 1,
        'agree_terms'          => 1,
        'privacy_settings'     => json_encode(['Email Address']),
        'status'               => 'pending',
        'is_active'            => 0,
        'is_star_alumni'       => 0,
        'star_description'     => null,
        'featured_text'        => null,
        'class_year'           => null,
        'remember_token'       => null,
        'created_at'           => '2026-06-18 12:44:59',
        'updated_at'           => '2026-06-18 12:45:01',
    ]
);

AlumniUser::updateOrCreate(
    ['email' => 'moominazahid449@gmail.com'],
    [
        'full_name'            => 'moomina',
        'password'             => '$2y$12$2gAKiMrIYwLQ80PpsSEDzuNvlufKkpayVsR7Acp06qfI/9G7qyfYy', // ⚠️ PASTE FULL HASH
        'entry'                => 14,
        'ccp_no'               => 1364,
        'house'                => 'Iqbal',
        'education'            => 'Bachelors',
        'field_of_study'       => 'SE',
        'field_of_work'        => 'DEV',
        'current_city'         => 'Islamabad',
        'current_country'      => 'Pakistan',
        'current_designation'  => 'DEV',
        'current_organization' => 'dev',
        'phone_number'         => '+921234567891',
        'achievements'         => 'ffffee',
        'profile_photo'        => 'profiles/profile_5_1781790110.png',
        'cnic_file'            => 'cnics/cnic_5_1781790110.png',
        'consent_sharing'      => 1,
        'agree_terms'          => 1,
        'privacy_settings'     => json_encode(['City', 'Phone Number']),
        'status'               => 'pending',
        'is_active'            => 0,
        'is_star_alumni'       => 0,
        'star_description'     => null,
        'featured_text'        => null,
        'class_year'           => null,
        'remember_token'       => null,
        'created_at'           => '2026-06-18 13:41:50',
        'updated_at'           => '2026-06-18 13:41:50',
    ]
);

        // ── CMS Default Settings ──────────────────────────────────────────────
        $defaults = [
            'hero_title'          => 'Welcome to POBA Alumni Network',
            'hero_tagline'        => 'Serving with Valour',
            'hero_description'    => 'Join our prestigious community of Pakistan Ocean & Bay Alumni. Stay connected, share experiences, and build lasting professional relationships.',
            'hero_btn_text'       => 'Become a Member',
            'about_title'         => 'About POBA',
            'about_description'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'about_btn_text'      => 'Become a Member',
            'mission_title'       => 'Our Mission',
            'mission_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'history_title'       => 'Our History',
            'history_description' => "Milestones in POBA's journey of excellence",
            'history_timeline'    => json_encode([
                ['year' => '1947', 'heading' => 'Foundation Era',        'description' => 'Establishment of Pakistan Navy and the beginning of naval education traditions.'],
                ['year' => '1965', 'heading' => 'First Alumni Network',  'description' => 'Formation of the first organized alumni association.'],
                ['year' => '1980', 'heading' => 'Formal Constitution',   'description' => 'POBA officially constituted with formal structure and governance framework.'],
                ['year' => '1995', 'heading' => 'Modernization Phase',   'description' => 'Introduction of modern communication systems and expanded alumni services.'],
                ['year' => '2010', 'heading' => 'Digital Transformation','description' => 'Launch of digital platforms for better alumni connectivity.'],
                ['year' => '2025', 'heading' => 'New Horizons',          'description' => 'Comprehensive website launch and enhanced alumni engagement initiatives.'],
            ]),
            'contact_email'    => 'info@poba.com',
            'contact_number'   => '+92 21 123 4567',
            'location'         => 'Cadet College Palandri, AJK',
            'bank_title'       => 'Bank of AJK',
            'account_title'    => 'Palandarians Old Boys Association',
            'account_number'   => '00001234657980',
            'branch_number'    => '063',
            'footer_copyright' => '© 2025 POBA. All rights reserved.',
            'seo_title'        => 'POBA - Pakistan Ocean & Bay Alumni | Official Alumni Network',
            'seo_keywords'     => 'POBA, Pakistan Ocean Bay Alumni, Pakistan Navy Alumni, Naval Officers Network',
            'seo_description'  => 'Official Pakistan Ocean & Bay Alumni (POBA) network. Est. 1947.',
        ];

        foreach ($defaults as $key => $value) {
            CmsSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // ── Default FAQs ──────────────────────────────────────────────────────
        $faqs = [
            ['question' => 'What is POBA?',                'answer' => "POBA stands for Palandarians' Old Boys' Association — the official alumni network of Cadet College Palandri.", 'sort_order' => 1],
            ['question' => 'How do I become a member?',    'answer' => 'Click "Become a Member" and fill out the Alumni Registration Form. Admin will review and approve your application.', 'sort_order' => 2],
            ['question' => 'How long does approval take?', 'answer' => 'Applications are reviewed within 3–5 business days. You will receive an email once approved.', 'sort_order' => 3],
            ['question' => 'How do I register for events?','answer' => 'Browse the Events page and click "Register Now". You must be a logged-in approved member.', 'sort_order' => 4],
            ['question' => 'Is my information private?',   'answer' => 'You control your privacy settings during registration. You can hide your email, phone, or city from other alumni.', 'sort_order' => 5],
            ['question' => 'Can I update my profile?',     'answer' => 'Yes. Contact the admin team to update your profile information at any time.', 'sort_order' => 6],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
