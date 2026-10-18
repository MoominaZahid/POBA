<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlumniDuplicateEmailAndPhoneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
        Storage::fake('public');
    }

    private function createFakeFile(string $name = 'document.png'): UploadedFile
    {
        $content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAA=');
        return UploadedFile::fake()->createWithContent($name, $content);
    }

    private function validRegistrationPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name'       => 'Test Alumnus',
            'email'           => 'unique.alumnus@poba.test',
            'phone_code'      => '+92',
            'phone_number'    => '3009876543',
            'entry'           => '15',
            'class_year'      => '2010',
            'ccp_no'          => '55443',
            'house'           => 'Jinnah',
            'education'       => 'Bachelors',
            'field_of_study'  => 'Computer Science',
            'field_of_work'   => 'Software',
            'current_city'    => 'Islamabad',
            'current_country' => 'Pakistan',
            'cnic_file'       => $this->createFakeFile('cnic.png'),
            'captcha_answer'  => '12',
            'consent_sharing' => '1',
            'agree_terms'     => '1',
        ], $overrides);
    }

    public function test_registration_fails_when_email_already_exists_in_alumni_users()
    {
        AlumniUser::create([
            'full_name'       => 'Existing Alumnus',
            'email'           => 'existing@poba.test',
            'phone_number'    => '+923001111111',
            'ccp_no'          => '1111',
            'entry'           => 10,
            'status'          => 'approved',
            'is_active'       => true,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ]);

        $payload = $this->validRegistrationPayload([
            'email'        => 'existing@poba.test',
            'ccp_no'       => '2222',
            'phone_number' => '3009999999',
        ]);

        $response = $this->withSession(['captcha_ans' => '12'])
            ->post(route('member.store'), $payload);

        $response->assertSessionHasErrors([
            'email' => 'This email address is already associated with an existing account.',
        ]);
    }

    public function test_registration_fails_when_email_matches_case_insensitively()
    {
        AlumniUser::create([
            'full_name'       => 'Existing Alumnus',
            'email'           => 'existing@poba.test',
            'phone_number'    => '+923001111111',
            'ccp_no'          => '1111',
            'entry'           => 10,
            'status'          => 'approved',
            'is_active'       => true,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ]);

        $payload = $this->validRegistrationPayload([
            'email'        => '  EXISTING@POBA.TEST  ',
            'ccp_no'       => '2222',
            'phone_number' => '3009999999',
        ]);

        $response = $this->withSession(['captcha_ans' => '12'])
            ->post(route('member.store'), $payload);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_fails_when_email_exists_in_users_admin_table()
    {
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@poba.test',
            'password' => bcrypt('secret123'),
            'role'     => 'superadmin',
        ]);

        $payload = $this->validRegistrationPayload([
            'email'        => 'admin@poba.test',
            'ccp_no'       => '3333',
            'phone_number' => '3009999999',
        ]);

        $response = $this->withSession(['captcha_ans' => '12'])
            ->post(route('member.store'), $payload);

        $response->assertSessionHasErrors([
            'email' => 'This email address is already associated with an existing account.',
        ]);
    }

    public function test_registration_fails_when_phone_number_already_registered_exact_match()
    {
        AlumniUser::create([
            'full_name'       => 'Existing Alumnus',
            'email'           => 'existing1@poba.test',
            'phone_number'    => '+923001234567',
            'ccp_no'          => '1111',
            'entry'           => 10,
            'status'          => 'approved',
            'is_active'       => true,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ]);

        $payload = $this->validRegistrationPayload([
            'email'        => 'newperson@poba.test',
            'phone_code'   => '+92',
            'phone_number' => '3001234567',
            'ccp_no'       => '2222',
        ]);

        $response = $this->withSession(['captcha_ans' => '12'])
            ->post(route('member.store'), $payload);

        $response->assertSessionHasErrors([
            'phone_number' => 'This phone number is already associated with an existing account.',
        ]);
    }

    public function test_registration_fails_when_phone_number_entered_with_leading_zero()
    {
        AlumniUser::create([
            'full_name'       => 'Existing Alumnus',
            'email'           => 'existing2@poba.test',
            'phone_number'    => '+923001234567',
            'ccp_no'          => '1111',
            'entry'           => 10,
            'status'          => 'approved',
            'is_active'       => true,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ]);

        // Input with leading 0 (03001234567) under +92
        $payload = $this->validRegistrationPayload([
            'email'        => 'newperson2@poba.test',
            'phone_code'   => '+92',
            'phone_number' => '03001234567',
            'ccp_no'       => '3333',
        ]);

        $response = $this->withSession(['captcha_ans' => '12'])
            ->post(route('member.store'), $payload);

        $response->assertSessionHasErrors([
            'phone_number' => 'This phone number is already associated with an existing account.',
        ]);
    }

    public function test_registration_fails_when_existing_phone_number_has_spaces_or_formatting()
    {
        AlumniUser::create([
            'full_name'       => 'Existing Alumnus',
            'email'           => 'existing3@poba.test',
            'phone_number'    => '+92 345 450 1450',
            'ccp_no'          => '1111',
            'entry'           => 10,
            'status'          => 'approved',
            'is_active'       => true,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ]);

        $payload = $this->validRegistrationPayload([
            'email'        => 'newperson3@poba.test',
            'phone_code'   => '+92',
            'phone_number' => '3454501450',
            'ccp_no'       => '4444',
        ]);

        $response = $this->withSession(['captcha_ans' => '12'])
            ->post(route('member.store'), $payload);

        $response->assertSessionHasErrors([
            'phone_number' => 'This phone number is already associated with an existing account.',
        ]);
    }

    public function test_registration_succeeds_with_new_unique_email_and_phone()
    {
        $payload = $this->validRegistrationPayload([
            'email'        => 'brandnew@poba.test',
            'phone_code'   => '+92',
            'phone_number' => '3119876543',
            'ccp_no'       => '99887',
        ]);

        $response = $this->withSession(['captcha_ans' => '12'])
            ->post(route('member.store'), $payload);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('registration_success');

        $created = AlumniUser::where('email', 'brandnew@poba.test')->first();
        $this->assertNotNull($created);
        $this->assertEquals('+923119876543', $created->phone_number);
    }

    public function test_profile_update_allows_keeping_own_phone_but_blocks_taking_another_alumni_phone()
    {
        $userA = AlumniUser::create([
            'full_name'       => 'User A',
            'email'           => 'userA@poba.test',
            'phone_number'    => '+923001111111',
            'ccp_no'          => '1001',
            'entry'           => 10,
            'status'          => 'approved',
            'is_active'       => true,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ]);

        AlumniUser::create([
            'full_name'       => 'User B',
            'email'           => 'userB@poba.test',
            'phone_number'    => '+923002222222',
            'ccp_no'          => '1002',
            'entry'           => 10,
            'status'          => 'approved',
            'is_active'       => true,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ]);

        // 1. User A updates profile and keeps own phone number -> SUCCESS
        $response1 = $this->actingAs($userA, 'alumni')
            ->put(route('profile.update'), [
                'full_name'    => 'User A Updated',
                'phone_number' => '+923001111111',
            ]);
        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        // 2. User A attempts to update phone to User B's phone -> FAILS
        $response2 = $this->actingAs($userA, 'alumni')
            ->put(route('profile.update'), [
                'full_name'    => 'User A Updated',
                'phone_code'   => '+92',
                'phone_number' => '3002222222',
            ]);
        $response2->assertSessionHasErrors([
            'phone_number' => 'This phone number is already registered to another user.',
        ]);
    }
}
