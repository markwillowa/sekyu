<?php

namespace Tests\Feature;

use App\Enums\Pro\AccountStatus;
use App\Enums\Pro\UserRole;
use App\Models\Agency;
use App\Models\Pro\AgencyUser;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('applicant');

        // Create some notifications
        $user->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'data' => ['message' => 'Test 1'],
            'read_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('notifications.mark-all-read'));

        $response->assertRedirect();
        $this->assertEquals(0, $user->unreadNotifications()->count());
    }

    public function test_user_can_clear_all_notifications()
    {
        $user = User::factory()->create();
        $user->assignRole('applicant');

        $user->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'data' => ['message' => 'Test 1'],
            'read_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('notifications.clear'));

        $response->assertRedirect();
        $this->assertEquals(0, $user->notifications()->count());
    }

    public function test_guest_cannot_access_notification_actions()
    {
        $response = $this->post(route('notifications.mark-all-read'));
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('notifications.clear'));
        $response->assertRedirect(route('login'));
    }

    public function test_pro_agency_user_can_manage_notifications()
    {
        $agency = Agency::factory()->create();
        $agencyUser = AgencyUser::create([
            'agency_id' => $agency->id,
            'username' => 'pro-agency-admin',
            'password' => 'password',
            'name' => 'PRO Agency Admin',
            'role' => UserRole::Owner->value,
            'status' => AccountStatus::Active->value,
            'force_password_change' => false,
        ]);

        $agencyUser->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'data' => ['message' => 'Test 1'],
            'read_at' => null,
        ]);

        $this->actingAs($agencyUser, 'pro_agency');

        $this
            ->post(route('pro.notifications.mark-all-read'))
            ->assertRedirect();

        $this->assertSame(0, $agencyUser->unreadNotifications()->count());

        $this
            ->delete(route('pro.notifications.clear'))
            ->assertRedirect();

        $this->assertSame(0, $agencyUser->notifications()->count());
    }
}
