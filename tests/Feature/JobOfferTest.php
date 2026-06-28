<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\JobPost;
use App\Models\User;
use App\Models\WorkflowTemplate;
use App\Models\WorkflowTemplateStep;
use App\Notifications\JobOfferResponse;
use App\Notifications\JobOfferSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobOfferTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        Storage::fake('public');
    }

    public function test_agency_can_upload_job_offer_pdf()
    {
        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);

        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id]);
        $applicant = User::factory()->create();
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-UPLOAD',
            'salary' => 50000,
            'employment_type' => 'Full-time',
            'start_date' => now()->toDateString(),
            'location' => 'Test',
            'status' => 'Draft',
        ]);

        $this->actingAs($agencyUser);

        $file = UploadedFile::fake()->create('offer.pdf', 100, 'application/pdf');

        $response = $this->post(route('agency.offers.upload-pdf', $offer), [
            'offer_letter' => $file,
        ]);

        $response->assertStatus(302);
        $this->assertCount(1, $offer->fresh()->getMedia('offer_letter'));
    }

    public function test_guard_can_download_job_offer_pdf()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $agency = Agency::factory()->create();
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id]);
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-DOWNLOAD',
            'salary' => 50000,
            'employment_type' => 'Full-time',
            'start_date' => now()->toDateString(),
            'location' => 'Test',
            'status' => 'Sent',
        ]);

        $file = UploadedFile::fake()->create('offer.pdf', 100, 'application/pdf');
        $offer->addMedia($file)->toMediaCollection('offer_letter');

        $this->actingAs($applicant);

        $response = $this->get(route('applicant.offers.download', $offer));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'offer.pdf'));
    }

    public function test_agency_can_create_job_offer_draft()
    {
        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);

        $workflow = WorkflowTemplate::create(['agency_id' => $agency->id, 'name' => 'Test']);
        $step = WorkflowTemplateStep::create(['workflow_template_id' => $workflow->id, 'name' => 'Job Offer', 'sort_order' => 1]);
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id, 'workflow_template_id' => $workflow->id]);

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'current_workflow_step_id' => $step->id,
            'applied_at' => now(),
        ]);

        $this->actingAs($agencyUser);

        $response = $this->post(route('agency.applications.offers.store', $application), [
            'salary' => 50000,
            'employment_type' => 'Full-time',
            'start_date' => now()->addDays(7)->toDateString(),
            'location' => 'Main Office',
            'benefits' => 'Health, Dental',
            'remarks' => 'Welcome aboard',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('job_offers', [
            'job_application_id' => $application->id,
            'salary' => 50000,
            'status' => 'Draft',
        ]);
    }

    public function test_agency_can_send_job_offer()
    {
        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);

        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id]);
        $applicant = User::factory()->create();
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-123',
            'salary' => 50000,
            'employment_type' => 'Full-time',
            'start_date' => now()->toDateString(),
            'location' => 'Test',
            'status' => 'Draft',
        ]);

        $this->actingAs($agencyUser);

        Notification::fake();

        $response = $this->post(route('agency.offers.send', $offer));

        $response->assertStatus(302);
        $this->assertEquals('Sent', $offer->fresh()->status);

        Notification::assertSentTo(
            $applicant,
            JobOfferSent::class,
            function ($notification, $channels) use ($offer) {
                return $notification->offer->id === $offer->id;
            }
        );
    }

    public function test_guard_can_accept_job_offer()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $agency = Agency::factory()->create();
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id]);
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-123',
            'salary' => 50000,
            'employment_type' => 'Full-time',
            'start_date' => now()->toDateString(),
            'location' => 'Test',
            'status' => 'Sent',
        ]);

        $this->actingAs($applicant);

        Notification::fake();

        $response = $this->post(route('applicant.offers.accept', $offer));

        $response->assertStatus(302);
        $this->assertEquals('Accepted', $offer->fresh()->status);
        $this->assertNotNull($offer->fresh()->accepted_at);

        Notification::assertSentTo(
            $agency->owner,
            JobOfferResponse::class,
            function ($notification) use ($offer) {
                return $notification->offer->id === $offer->id && $notification->status === 'Accepted';
            }
        );
    }

    public function test_guard_can_decline_job_offer()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $agencyUser = User::factory()->create();
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id]);
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-DECLINE',
            'salary' => 50000,
            'employment_type' => 'Full-time',
            'start_date' => now()->toDateString(),
            'location' => 'Test',
            'status' => 'Sent',
        ]);

        $this->actingAs($applicant);

        Notification::fake();

        $response = $this->post(route('applicant.offers.decline', $offer));

        $response->assertStatus(302);
        $this->assertEquals('Declined', $offer->fresh()->status);
        $this->assertNotNull($offer->fresh()->declined_at);

        Notification::assertSentTo(
            $agencyUser,
            JobOfferResponse::class,
            function ($notification) use ($offer) {
                return $notification->offer->id === $offer->id && $notification->status === 'Declined';
            }
        );
    }

    public function test_job_application_has_job_offer_relationship()
    {
        $jobPost = JobPost::factory()->create();
        $applicant = User::factory()->create();
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-TEST',
            'salary' => 50000,
            'employment_type' => 'Full-time',
            'start_date' => now()->toDateString(),
            'location' => 'Test',
            'status' => 'Sent',
        ]);

        // Test eager loading which was failing
        $loadedApplication = JobApplication::with('jobOffer')->find($application->id);

        $this->assertNotNull($loadedApplication->jobOffer);
        $this->assertEquals($offer->id, $loadedApplication->jobOffer->id);
    }
}
