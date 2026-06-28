<?php

namespace Tests\Feature;

use App\Enums\Pro\AccountStatus;
use App\Enums\Pro\UserRole;
use App\Models\Agency;
use App\Models\Conversation;
use App\Models\GuardProfile;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\Message;
use App\Models\Pro\AgencyUser;
use App\Models\User;
use App\Notifications\NewConversationMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProAgencyMessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_pro_agency_user_can_view_messenger_style_conversations(): void
    {
        $agencyUser = $this->agencyUser();
        $firstConversation = $this->conversationForAgency($agencyUser->agency, 'Maria Santos', 'Security Guard', [
            'Hello, I am available for interview.',
        ]);
        $secondConversation = $this->conversationForAgency($agencyUser->agency, 'Juan Cruz', 'Security Supervisor', [
            'Please bring your license tomorrow.',
        ]);

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->get(route('pro.agency.messages.index'))
            ->assertOk()
            ->assertSee('Messages')
            ->assertSee('Maria Santos')
            ->assertSee('Juan Cruz')
            ->assertSee('Hello, I am available for interview.');

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->get(route('pro.agency.messages.index', ['conversation' => $secondConversation]))
            ->assertOk()
            ->assertSee('Juan Cruz')
            ->assertSee('Security Supervisor')
            ->assertSee('Please bring your license tomorrow.');

        $this->assertSame($agencyUser->agency_id, $firstConversation->application->job->agency_id);
    }

    public function test_pro_agency_user_only_sees_own_agency_conversations(): void
    {
        $agencyUser = $this->agencyUser();
        $this->conversationForAgency($agencyUser->agency, 'Maria Santos', 'Security Guard', [
            'Visible agency message.',
        ]);

        $otherAgency = Agency::factory()->create();
        $otherConversation = $this->conversationForAgency($otherAgency, 'Other Applicant', 'Other Job', [
            'Private other agency message.',
        ]);

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->get(route('pro.agency.messages.index'))
            ->assertOk()
            ->assertSee('Maria Santos')
            ->assertDontSee('Other Applicant')
            ->assertDontSee('Private other agency message.');

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->get(route('pro.agency.messages.index', ['conversation' => $otherConversation]))
            ->assertForbidden();
    }

    public function test_pro_agency_user_can_send_message_to_applicant(): void
    {
        $agencyUser = $this->agencyUser();
        $conversation = $this->conversationForAgency($agencyUser->agency, 'Maria Santos', 'Security Guard', [
            'Hello, I am available for interview.',
        ]);
        $guard = $conversation->application->applicant;

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->post(route('pro.agency.messages.send', $conversation), [
                'message' => 'Thanks Maria. Please proceed to the office at 9 AM.',
            ])
            ->assertRedirect(route('pro.agency.messages.index', ['conversation' => $conversation]));

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $agencyUser->agency->owner_id,
            'message' => 'Thanks Maria. Please proceed to the office at 9 AM.',
        ]);

        $this->assertSame(
            1,
            $guard->notifications()->where('type', NewConversationMessage::class)->count()
        );

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->get(route('pro.agency.messages.index', ['conversation' => $conversation]))
            ->assertOk()
            ->assertSee('Thanks Maria. Please proceed to the office at 9 AM.');
    }

    public function test_pro_agency_user_cannot_send_message_to_other_agency_conversation(): void
    {
        $agencyUser = $this->agencyUser();
        $otherAgency = Agency::factory()->create();
        $otherConversation = $this->conversationForAgency($otherAgency, 'Other Applicant', 'Other Job', [
            'Private other agency message.',
        ]);

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->post(route('pro.agency.messages.send', $otherConversation), [
                'message' => 'This should not send.',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $otherConversation->id,
            'message' => 'This should not send.',
        ]);
    }

    private function agencyUser(): AgencyUser
    {
        $agency = Agency::factory()->create();

        return AgencyUser::create([
            'agency_id' => $agency->id,
            'username' => 'agency-admin',
            'password' => 'password',
            'name' => 'Agency Admin',
            'role' => UserRole::Owner->value,
            'status' => AccountStatus::Active->value,
            'force_password_change' => false,
        ]);
    }

    private function conversationForAgency(Agency $agency, string $applicantName, string $jobTitle, array $messages): Conversation
    {
        $applicant = User::factory()->create([
            'name' => $applicantName,
        ]);

        [$firstName, $lastName] = array_pad(explode(' ', $applicantName, 2), 2, null);

        GuardProfile::create([
            'user_id' => $applicant->id,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $job = JobPost::create([
            'agency_id' => $agency->id,
            'title' => $jobTitle,
            'slug' => str($jobTitle)->slug().'-'.uniqid(),
            'description' => 'Security role.',
            'vacancies' => 1,
        ]);

        $application = JobApplication::create([
            'job_id' => $job->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        $conversation = Conversation::create([
            'job_application_id' => $application->id,
        ]);

        foreach ($messages as $message) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $applicant->id,
                'message' => $message,
            ]);
        }

        return $conversation;
    }
}
