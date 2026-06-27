<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\JobPost;
use App\Models\User;
use App\Models\WorkflowTemplate;
use App\Notifications\NewJobApplication;
use Database\Seeders\MasterDataSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AgencyNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_agency_owner_receives_notification_when_applicant_applies()
    {
        // Seed necessary data
        $this->seed(MasterDataSeeder::class);
        $this->seed(RoleSeeder::class);

        Notification::fake();

        // Create agency owner
        $agencyOwner = User::factory()->create();
        $agencyOwner->assignRole('agency');

        // Create agency
        $agency = Agency::factory()->create([
            'owner_id' => $agencyOwner->id,
        ]);

        // Create workflow template and steps
        $template = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Test Template',
        ]);
        $step = $template->steps()->create([
            'name' => 'Application Received',
            'type' => 'normal',
            'sort_order' => 1,
        ]);

        // Create job post
        $jobPost = JobPost::factory()->create([
            'agency_id' => $agency->id,
            'workflow_template_id' => $template->id,
        ]);

        // Create applicant
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $this->actingAs($applicant);

        $response = $this->post(route('jobs.apply', $jobPost));

        $response->assertStatus(302);

        Notification::assertSentTo(
            $agencyOwner,
            NewJobApplication::class,
            function ($notification, $channels) use ($jobPost, $applicant) {
                return $notification->application->job_id === $jobPost->id &&
                       $notification->application->guard_id === $applicant->id;
            }
        );
    }
}
