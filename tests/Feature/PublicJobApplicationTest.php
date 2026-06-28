<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\JobPost;
use App\Models\User;
use App\Models\WorkflowTemplate;
use App\Models\WorkflowTemplateStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicJobApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_applicant_can_apply_via_ajax()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $agency = Agency::factory()->create();

        $workflow = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Default Workflow'
        ]);

        WorkflowTemplateStep::create([
            'workflow_template_id' => $workflow->id,
            'name' => 'Applied',
            'sort_order' => 1
        ]);

        $jobPost = JobPost::factory()->create([
            'agency_id' => $agency->id,
            'workflow_template_id' => $workflow->id,
            'published_at' => now()
        ]);

        $this->actingAs($applicant);

        $response = $this->postJson(route('jobs.apply', $jobPost));

        $response->assertStatus(200)
            ->assertJson([
                'success' => 'Your application has been submitted successfully!'
            ]);

        $this->assertDatabaseHas('job_applications', [
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id
        ]);
    }

    public function test_applicant_cannot_apply_twice_via_ajax()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $agency = Agency::factory()->create();

        $workflow = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Default Workflow'
        ]);

        WorkflowTemplateStep::create([
            'workflow_template_id' => $workflow->id,
            'name' => 'Applied',
            'sort_order' => 1
        ]);

        $jobPost = JobPost::factory()->create([
            'agency_id' => $agency->id,
            'workflow_template_id' => $workflow->id,
            'published_at' => now()
        ]);

        $this->actingAs($applicant);

        // First application
        $this->postJson(route('jobs.apply', $jobPost));

        // Second application
        $response = $this->postJson(route('jobs.apply', $jobPost));

        $response->assertStatus(422)
            ->assertJson([
                'error' => 'You have already applied for this job.'
            ]);
    }
    public function test_applicant_cannot_apply_if_profile_completion_is_below_requirement()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        // Create an empty profile
        $applicant->guardProfile()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birth_date' => '1990-01-01',
            'nationality' => 'Filipino',
        ]);

        $agency = Agency::factory()->create();

        $workflow = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Default Workflow'
        ]);

        WorkflowTemplateStep::create([
            'workflow_template_id' => $workflow->id,
            'name' => 'Applied',
            'sort_order' => 1
        ]);

        $jobPost = JobPost::factory()->create([
            'agency_id' => $agency->id,
            'workflow_template_id' => $workflow->id,
            'published_at' => now(),
            'min_profile_completion' => 80 // High requirement
        ]);

        $this->actingAs($applicant);

        $response = $this->postJson(route('jobs.apply', $jobPost));

        $response->assertStatus(422)
            ->assertJsonStructure(['error']);

        $this->assertDatabaseMissing('job_applications', [
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id
        ]);
    }

    public function test_applicant_can_apply_if_profile_completion_meets_requirement()
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $applicant->guardProfile()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birth_date' => '1990-01-01',
            'nationality' => 'Filipino',
        ]);

        $agency = Agency::factory()->create();

        $workflow = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Default Workflow'
        ]);

        WorkflowTemplateStep::create([
            'workflow_template_id' => $workflow->id,
            'name' => 'Applied',
            'sort_order' => 1
        ]);

        $jobPost = JobPost::factory()->create([
            'agency_id' => $agency->id,
            'workflow_template_id' => $workflow->id,
            'published_at' => now(),
            'min_profile_completion' => 0 // No requirement
        ]);

        $this->actingAs($applicant);

        $response = $this->postJson(route('jobs.apply', $jobPost));

        $response->assertStatus(200);

        $this->assertDatabaseHas('job_applications', [
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id
        ]);
    }
}
