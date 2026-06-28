<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuardDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_can_view_dashboard_without_pro_agency_guard(): void
    {
        $this->seed(RoleSeeder::class);

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $this
            ->actingAs($applicant)
            ->get(route('applicant.dashboard'))
            ->assertOk()
            ->assertSee('Guard Dashboard')
            ->assertSee($applicant->name);
    }
}
