<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();

        // Create some notifications
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
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

        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
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
}
