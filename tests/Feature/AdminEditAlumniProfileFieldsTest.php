<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEditAlumniProfileFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    private function createAdmin(): User
    {
        return User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@poba.test',
            'password' => bcrypt('secret123'),
            'role'     => 'superadmin',
        ]);
    }

    public function test_admin_can_view_alumni_profile_with_editable_country_city_desig_org()
    {
        $admin = $this->createAdmin();

        Country::create(['name' => 'Pakistan']);
        Country::create(['name' => 'United States']);

        $alumni = AlumniUser::create([
            'full_name'            => 'Tariq Mehmood',
            'email'                => 'tariq@poba.test',
            'phone_number'         => '+923001234567',
            'entry'                => 15,
            'ccp_no'               => '4321',
            'house'                => 'Jinnah',
            'status'               => 'approved',
            'is_active'            => true,
            'current_country'      => 'Pakistan',
            'current_city'         => 'Islamabad',
            'current_designation'  => 'Project Manager',
            'current_organization' => 'Apex Solutions',
            'consent_sharing'      => true,
            'agree_terms'          => true,
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.alumni.show', $alumni->id));

        $response->assertStatus(200);
        $response->assertSee('name="current_designation"', false);
        $response->assertSee('name="current_organization"', false);
        $response->assertSee('name="current_country"', false);
        $response->assertSee('name="current_city"', false);
        $response->assertSee('Pakistan');
        $response->assertSee('United States');
        $response->assertSee('value="Project Manager"', false);
        $response->assertSee('value="Apex Solutions"', false);
    }

    public function test_admin_can_update_alumni_city_country_designation_and_organization()
    {
        $admin = $this->createAdmin();

        $alumni = AlumniUser::create([
            'full_name'            => 'Tariq Mehmood',
            'email'                => 'tariq2@poba.test',
            'phone_number'         => '+923001234567',
            'entry'                => 15,
            'ccp_no'               => '9876',
            'house'                => 'Jinnah',
            'status'               => 'approved',
            'is_active'            => true,
            'current_country'      => 'Pakistan',
            'current_city'         => 'Islamabad',
            'current_designation'  => 'Old Role',
            'current_organization' => 'Old Org',
            'consent_sharing'      => true,
            'agree_terms'          => true,
        ]);

        $response = $this->actingAs($admin, 'web')->put(route('admin.alumni.update', $alumni->id), [
            'full_name'            => 'Tariq Mehmood',
            'current_city'         => 'Lahore',
            'current_country'      => 'Pakistan',
            'current_designation'  => 'Senior Engineering Director',
            'current_organization' => 'Tech Global Systems',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $alumni->refresh();
        $this->assertEquals('Lahore', $alumni->current_city);
        $this->assertEquals('Pakistan', $alumni->current_country);
        $this->assertEquals('Senior Engineering Director', $alumni->current_designation);
        $this->assertEquals('Tech Global Systems', $alumni->current_organization);
    }
}
