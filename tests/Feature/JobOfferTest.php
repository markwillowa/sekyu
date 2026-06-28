<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\JobPost;
use App\Models\MasterEmploymentType;
use App\Models\MasterJobOfferStatus;
use App\Models\MasterLocation;
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
        $this->seed(\Database\Seeders\MasterDataSeeder::class);
        $this->seed(\Database\Seeders\MasterJobOfferStatusSeeder::class);
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

        $draftStatus = MasterJobOfferStatus::where('code', 'draft')->first();

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-UPLOAD',
            'salary' => 50000,
            'employment_type_id' => MasterEmploymentType::first()->id,
            'start_date' => now()->toDateString(),
            'location_id' => MasterLocation::first()->id,
            'status_id' => $draftStatus->id,
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

        $sentStatus = MasterJobOfferStatus::where('code', 'sent')->first();

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-DOWNLOAD',
            'salary' => 50000,
            'employment_type_id' => MasterEmploymentType::first()->id,
            'start_date' => now()->toDateString(),
            'location_id' => MasterLocation::first()->id,
            'status_id' => $sentStatus->id,
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

        MasterJobOfferStatus::where('code', 'draft')->delete();

        $response = $this->post(route('agency.applications.offers.store', $application), [
            'salary' => 50000,
            'employment_type_id' => MasterEmploymentType::first()->id,
            'start_date' => now()->addDays(7)->toDateString(),
            'location_id' => MasterLocation::first()->id,
            'benefits' => 'Health, Dental',
            'remarks' => 'Welcome aboard',
        ]);

        $response->assertStatus(302);
        $draftStatus = MasterJobOfferStatus::where('code', 'draft')->first();
        $this->assertDatabaseHas('job_offers', [
            'job_application_id' => $application->id,
            'salary' => 50000,
            'status_id' => $draftStatus->id,
        ]);
    }

    public function test_invalid_job_offer_submission_reopens_offer_modal_without_login_context()
    {
        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);

        $workflow = WorkflowTemplate::create(['agency_id' => $agency->id, 'name' => 'Test']);
        $step = WorkflowTemplateStep::create(['workflow_template_id' => $workflow->id, 'name' => 'Job Offer', 'sort_order' => 1]);
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id, 'workflow_template_id' => $workflow->id]);

        $applicant = User::factory()->create();
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'current_workflow_step_id' => $step->id,
            'applied_at' => now(),
        ]);

        $response = $this
            ->actingAs($agencyUser)
            ->from(route('agency.applications.show', $application))
            ->post(route('agency.applications.offers.store', $application), [
                'salary' => 50000,
            ]);

        $response
            ->assertRedirect(route('agency.applications.show', $application))
            ->assertSessionHas('trigger_modal', 'create-job-offer')
            ->assertSessionHasErrors([
                'employment_type_id',
                'start_date',
                'location_id',
            ]);

        $this->assertArrayNotHasKey('account_type', session()->getOldInput());
    }

    public function test_application_offer_form_uses_master_ids_for_offer_fields()
    {
        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);

        $workflow = WorkflowTemplate::create(['agency_id' => $agency->id, 'name' => 'Test']);
        $step = WorkflowTemplateStep::create(['workflow_template_id' => $workflow->id, 'name' => 'Job Offer', 'sort_order' => 1]);
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id, 'workflow_template_id' => $workflow->id]);

        $applicant = User::factory()->create();
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'current_workflow_step_id' => $step->id,
            'applied_at' => now(),
        ]);

        $response = $this
            ->actingAs($agencyUser)
            ->get(route('agency.applications.show', $application));

        $response
            ->assertOk()
            ->assertSee('name="employment_type_id"', false)
            ->assertSee('name="location_id"', false)
            ->assertDontSee('name="employment_type"', false)
            ->assertDontSee('name="location"', false);
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

        $draftStatus = MasterJobOfferStatus::where('code', 'draft')->first();

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-123',
            'salary' => 50000,
            'employment_type_id' => MasterEmploymentType::first()->id,
            'start_date' => now()->toDateString(),
            'location_id' => MasterLocation::first()->id,
            'status_id' => $draftStatus->id,
        ]);

        $this->actingAs($agencyUser);

        Notification::fake();

        MasterJobOfferStatus::where('code', 'sent')->delete();

        $response = $this->post(route('agency.offers.send', $offer));

        $response->assertStatus(302);
        $this->assertEquals('sent', $offer->fresh()->status->code);

        Notification::assertSentTo(
            $applicant,
            JobOfferSent::class,
            function ($notification, $channels) use ($offer) {
                return $notification->offer->id === $offer->id;
            }
        );
    }

    public function test_job_offer_sent_notification_links_to_applicant_application()
    {
        $agency = Agency::factory()->create();
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id]);
        $applicant = User::factory()->create();

        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $sentStatus = MasterJobOfferStatus::where('code', 'sent')->first();

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-NOTIFY',
            'salary' => 50000,
            'employment_type_id' => MasterEmploymentType::first()->id,
            'start_date' => now()->toDateString(),
            'location_id' => MasterLocation::first()->id,
            'status_id' => $sentStatus->id,
        ]);

        $mail = (new JobOfferSent($offer))->toMail($applicant);

        $this->assertSame(
            route('applicant.applications.show', $application),
            $mail->actionUrl
        );
    }

    public function test_applicant_offer_card_displays_relationship_names()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $agency = Agency::factory()->create();
        $employmentType = MasterEmploymentType::first();
        $location = MasterLocation::first();
        $jobPost = JobPost::factory()->create([
            'agency_id' => $agency->id,
            'employment_type_id' => $employmentType->id,
            'location_id' => $location->id,
        ]);

        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $sentStatus = MasterJobOfferStatus::where('code', 'sent')->first();

        JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-CARD',
            'salary' => 25000,
            'employment_type_id' => $employmentType->id,
            'start_date' => '2026-06-29',
            'location_id' => $location->id,
            'status_id' => $sentStatus->id,
        ]);

        $response = $this
            ->actingAs($applicant)
            ->get(route('applicant.applications.show', $application));

        $response
            ->assertOk()
            ->assertSee('Job Offer')
            ->assertSee('Sent')
            ->assertSee($employmentType->name)
            ->assertSee($location->name)
            ->assertDontSee('{&quot;id&quot;', false)
            ->assertDontSee('{"id"', false);
    }

    public function test_guard_can_accept_job_offer()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id]);
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $sentStatus = MasterJobOfferStatus::where('code', 'sent')->first();

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-123',
            'salary' => 50000,
            'employment_type_id' => MasterEmploymentType::first()->id,
            'start_date' => now()->toDateString(),
            'location_id' => MasterLocation::first()->id,
            'status_id' => $sentStatus->id,
        ]);

        $this->actingAs($applicant);

        Notification::fake();

        MasterJobOfferStatus::where('code', 'accepted')->delete();

        $response = $this->post(route('applicant.offers.accept', $offer));

        $response->assertStatus(302);
        $this->assertEquals('accepted', $offer->fresh()->status->code);
        $this->assertNotNull($offer->fresh()->accepted_at);

        Notification::assertSentTo(
            $agencyUser,
            JobOfferResponse::class
        );
    }

    public function test_guard_can_decline_job_offer()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);
        $jobPost = JobPost::factory()->create(['agency_id' => $agency->id]);
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $sentStatus = MasterJobOfferStatus::where('code', 'sent')->first();

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-DECLINE',
            'salary' => 50000,
            'employment_type_id' => MasterEmploymentType::first()->id,
            'start_date' => now()->toDateString(),
            'location_id' => MasterLocation::first()->id,
            'status_id' => $sentStatus->id,
        ]);

        $this->actingAs($applicant);

        Notification::fake();

        MasterJobOfferStatus::where('code', 'declined')->delete();

        $response = $this->post(route('applicant.offers.decline', $offer));

        $response->assertStatus(302);
        $this->assertEquals('declined', $offer->fresh()->status->code);
        $this->assertNotNull($offer->fresh()->declined_at);

        Notification::assertSentTo(
            $agencyUser,
            JobOfferResponse::class
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

        $sentStatus = MasterJobOfferStatus::where('code', 'sent')->first();

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-TEST',
            'salary' => 50000,
            'employment_type_id' => MasterEmploymentType::first()->id,
            'start_date' => now()->toDateString(),
            'location_id' => MasterLocation::first()->id,
            'status_id' => $sentStatus->id,
        ]);

        // Test eager loading which was failing
        $loadedApplication = JobApplication::with('jobOffer')->find($application->id);

        $this->assertNotNull($loadedApplication->jobOffer);
        $this->assertEquals($offer->id, $loadedApplication->jobOffer->id);
    }
}
