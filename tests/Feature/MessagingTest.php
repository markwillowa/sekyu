<?php

namespace Tests\Feature;

use App\Enums\Pro\AccountStatus;
use App\Enums\Pro\UserRole;
use App\Models\Agency;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\Pro\AgencyUser;
use App\Models\User;
use App\Models\WorkflowTemplate;
use App\Notifications\NewConversationMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RoleSeeder;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_agency_and_guard_can_message_each_other()
    {
        // Setup Agency
        $agencyOwner = User::factory()->create();
        $agencyOwner->assignRole('agency');
        $agency = Agency::create([
            'owner_id' => $agencyOwner->id,
            'name' => 'Test Agency',
            'slug' => 'test-agency',
            'email' => 'agency@example.com',
            'phone' => '123456789',
            'address' => 'Test Address',
        ]);
        $proAgencyUser = AgencyUser::create([
            'agency_id' => $agency->id,
            'username' => 'pro-agency-admin',
            'password' => 'password',
            'name' => 'PRO Agency Admin',
            'role' => UserRole::Owner->value,
            'status' => AccountStatus::Active->value,
            'force_password_change' => false,
        ]);

        // Setup Workflow
        $workflow = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Test Workflow',
        ]);

        // Setup Job Post
        $jobPost = JobPost::create([
            'agency_id' => $agency->id,
            'workflow_template_id' => $workflow->id,
            'title' => 'Security Guard',
            'slug' => 'security-guard',
            'description' => 'Test Description',
            'requirements' => 'Test Requirements',
            'salary_min' => 1000,
            'salary_max' => 2000,
            'vacancies' => 5,
        ]);

        // Setup Guard
        $guard = User::factory()->create();
        $guard->assignRole('applicant');

        // Create Job Application
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $guard->id,
            'applied_at' => now(),
        ]);

        // 1. Guard visits messages page
        $this->actingAs($guard);
        $response = $this->get(route('applicant.applications.messages', $application));
        $response->assertStatus(200);
        $response->assertSee('Conversation for ' . $jobPost->title);

        // 2. Guard sends a message
        $conversation = $application->conversation()->first();
        $response = $this->post(route('applicant.conversations.messages.send', $conversation), [
            'message' => 'Hello, I am interested in this position.',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $guard->id,
            'message' => 'Hello, I am interested in this position.',
        ]);

        $this->assertSame(
            1,
            $agencyOwner->notifications()->where('type', NewConversationMessage::class)->count()
        );
        $this->assertSame(
            1,
            $proAgencyUser->notifications()->where('type', NewConversationMessage::class)->count()
        );
        $this->assertSame(
            'Hello, I am interested in this position.',
            $proAgencyUser->notifications()->first()->data['preview']
        );

        // 3. Agency owner visits messages page
        $this->actingAs($agencyOwner);
        $response = $this->get(route('agency.applications.messages', $application));
        $response->assertStatus(200);
        $response->assertSee('Hello, I am interested in this position.');

        // 4. Agency owner replies
        $response = $this->post(route('agency.conversations.messages.send', $conversation), [
            'message' => 'Good morning. Please bring your original license.',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $agencyOwner->id,
            'message' => 'Good morning. Please bring your original license.',
        ]);

        $this->assertSame(
            1,
            $guard->notifications()->where('type', NewConversationMessage::class)->count()
        );
        $this->assertSame(
            2,
            $proAgencyUser->notifications()->where('type', NewConversationMessage::class)->count()
        );

        // 5. Check if Guard can see the reply
        $this->actingAs($guard);
        $response = $this->get(route('applicant.applications.messages', $application));
        $response->assertSee('Good morning. Please bring your original license.');
    }

    public function test_cannot_access_other_conversations()
    {
        $user1 = User::factory()->create();
        $user1->assignRole('applicant');

        $user2 = User::factory()->create();
        $user2->assignRole('applicant');

        $agencyOwner = User::factory()->create();
        $agencyOwner->assignRole('agency');
        $agency = Agency::create([
            'owner_id' => $agencyOwner->id,
            'name' => 'Test Agency',
            'slug' => 'test-agency',
            'email' => 'agency@example.com',
            'phone' => '123456789',
            'address' => 'Test Address',
        ]);
        $workflow = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Test Workflow',
        ]);
        $jobPost = JobPost::create([
            'agency_id' => $agency->id,
            'workflow_template_id' => $workflow->id,
            'title' => 'Security Guard',
            'slug' => 'security-guard',
            'description' => 'Test Description',
            'requirements' => 'Test Requirements',
            'salary_min' => 1000,
            'salary_max' => 2000,
            'vacancies' => 5,
        ]);

        $application1 = JobApplication::create(['job_id' => $jobPost->id, 'guard_id' => $user1->id, 'applied_at' => now()]);

        $this->actingAs($user2);
        $response = $this->get(route('applicant.applications.messages', $application1));
        $response->assertStatus(403);
    }
    public function test_agency_and_guard_can_message_each_other_via_ajax()
    {
        // Setup Agency
        $agencyOwner = User::factory()->create();
        $agencyOwner->assignRole('agency');
        $agency = Agency::create([
            'owner_id' => $agencyOwner->id,
            'name' => 'Test Agency',
            'slug' => 'test-agency',
            'email' => 'agency@example.com',
            'phone' => '123456789',
            'address' => 'Test Address',
        ]);

        // Setup Workflow
        $workflow = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Test Workflow',
        ]);

        // Setup Job Post
        $jobPost = JobPost::create([
            'agency_id' => $agency->id,
            'workflow_template_id' => $workflow->id,
            'title' => 'Security Guard',
            'slug' => 'security-guard',
            'description' => 'Test Description',
            'requirements' => 'Test Requirements',
            'salary_min' => 1000,
            'salary_max' => 2000,
            'vacancies' => 5,
        ]);

        // Setup Guard
        $guard = User::factory()->create();
        $guard->assignRole('applicant');

        // Create Job Application
        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $guard->id,
            'applied_at' => now(),
        ]);

        // 1. Guard visits messages page via AJAX
        $this->actingAs($guard);
        $response = $this->getJson(route('applicant.applications.messages', $application));
        $response->assertStatus(200);
        $response->assertJsonStructure(['html', 'conversation_id']);
        $this->assertStringContainsString('Conversation for ' . $jobPost->title, $response->json('html'));

        // 2. Guard sends a message via AJAX
        $conversation = $application->conversation()->first();
        $response = $this->postJson(route('applicant.conversations.messages.send', $conversation), [
            'message' => 'Hello, I am interested in this position.',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['html', 'success']);
        $this->assertStringContainsString('Hello, I am interested in this position.', $response->json('html'));

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $guard->id,
            'message' => 'Hello, I am interested in this position.',
        ]);

        // 3. Agency owner visits messages page via AJAX
        $this->actingAs($agencyOwner);
        $response = $this->getJson(route('agency.applications.messages', $application));
        $response->assertStatus(200);
        $this->assertStringContainsString('Hello, I am interested in this position.', $response->json('html'));

        // 4. Agency owner replies via AJAX
        $response = $this->postJson(route('agency.conversations.messages.send', $conversation), [
            'message' => 'Good morning. Please bring your original license.',
        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('Good morning. Please bring your original license.', $response->json('html'));

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $agencyOwner->id,
            'message' => 'Good morning. Please bring your original license.',
        ]);
    }
}
