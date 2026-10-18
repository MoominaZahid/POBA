<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MemberRegistrationFileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    public function test_become_a_member_page_renders_file_upload_restriction_markup_and_error_elements()
    {
        $response = $this->get(route('member.index'));

        $response->assertStatus(200);

        // Inputs accept only jpg, jpeg, png, pdf
        $response->assertSee('id="cnic_input"', false);
        $response->assertSee('accept=".jpg,.jpeg,.png,.pdf"', false);
        $response->assertSee('id="photo_input"', false);

        // Error message containers
        $response->assertSee('id="cnic_error"', false);
        $response->assertSee('id="photo_error"', false);

        // Drag and drop elements and styling
        $response->assertSee('id="cnic_file_area"', false);
        $response->assertSee('id="photo_file_area"', false);
        $response->assertSee('bam-dragover', false);
        $response->assertSee('pointer-events:none', false);

        // Form validation message
        $response->assertSee('Invalid file format. Only PDF, JPG, and PNG are allowed.', false);
    }

    public function test_submitting_unsupported_mp4_for_cnic_returns_exact_error_message()
    {
        $payload = [
            'full_name'       => 'Test Member',
            'email'           => 'test.member@example.com',
            'phone_code'      => '+92',
            'phone_number'    => '3001234567',
            'entry'           => '25',
            'class_year'      => '2020',
            'ccp_no'          => 'CCP-TEST-001',
            'house'           => 'Jinnah',
            'education'       => 'Bachelors',
            'field_of_study'  => 'Computer Science',
            'field_of_work'   => 'Software',
            'current_city'    => 'Islamabad',
            'current_country' => 'Pakistan',
            'cnic_file'       => UploadedFile::fake()->create('my_video.mp4', 1024, 'video/mp4'),
            'captcha_answer'  => session('captcha_result', '4'),
            'consent_sharing' => '1',
            'agree_terms'     => '1',
        ];

        // Seed expected captcha in session
        $response = $this->withSession(['captcha_result' => 4])
                         ->post(route('member.store'), array_merge($payload, ['captcha_answer' => 4]));

        $response->assertSessionHasErrors([
            'cnic_file' => 'Invalid file format. Only PDF, JPG, and PNG are allowed.',
        ]);
    }

    public function test_submitting_unsupported_mp4_for_profile_photo_returns_exact_error_message()
    {
        $payload = [
            'full_name'       => 'Test Member 2',
            'email'           => 'test.member2@example.com',
            'phone_code'      => '+92',
            'phone_number'    => '3001234568',
            'entry'           => '25',
            'class_year'      => '2020',
            'ccp_no'          => 'CCP-TEST-002',
            'house'           => 'Jinnah',
            'education'       => 'Bachelors',
            'field_of_study'  => 'Computer Science',
            'field_of_work'   => 'Software',
            'current_city'    => 'Islamabad',
            'current_country' => 'Pakistan',
            'cnic_file'       => UploadedFile::fake()->create('cnic_doc.pdf', 500, 'application/pdf'),
            'profile_photo'   => UploadedFile::fake()->create('my_photo_video.mp4', 1024, 'video/mp4'),
            'captcha_answer'  => 4,
            'consent_sharing' => '1',
            'agree_terms'     => '1',
        ];

        $response = $this->withSession(['captcha_result' => 4])
                         ->post(route('member.store'), $payload);

        $response->assertSessionHasErrors([
            'profile_photo' => 'Invalid file format. Only PDF, JPG, and PNG are allowed.',
        ]);
    }
}
