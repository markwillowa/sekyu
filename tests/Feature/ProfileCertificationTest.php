<?php

namespace Tests\Feature;

use App\Models\GuardCertification;
use App\Models\GuardProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCertificationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $profile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->profile = GuardProfile::create([
            'user_id' => $this->user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    public function test_user_can_add_certification()
    {
        $response = $this->actingAs($this->user)->post(route('applicant.profile.store-certification'), [
            'name' => 'Advanced Security Training',
            'issuer' => 'Security Academy',
            'issued_at' => '2023-01-01',
            'expires_at' => '2025-01-01',
            'credential_id' => 'CERT-12345',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guard_certifications', [
            'guard_profile_id' => $this->profile->id,
            'name' => 'Advanced Security Training',
            'issuing_organization' => 'Security Academy',
            'issued_at' => '2023-01-01 00:00:00',
            'expires_at' => '2025-01-01 00:00:00',
            'credential_number' => 'CERT-12345',
        ]);
    }

    public function test_user_can_update_certification()
    {
        $certification = GuardCertification::create([
            'guard_profile_id' => $this->profile->id,
            'name' => 'Old Name',
            'issuing_organization' => 'Old Issuer',
        ]);

        $response = $this->actingAs($this->user)->patch(route('applicant.profile.update-certification', $certification), [
            'name' => 'New Name',
            'issuer' => 'New Issuer',
            'issued_at' => '2023-02-02',
            'expires_at' => '2025-02-02',
            'credential_id' => 'CERT-67890',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guard_certifications', [
            'id' => $certification->id,
            'name' => 'New Name',
            'issuing_organization' => 'New Issuer',
            'credential_number' => 'CERT-67890',
        ]);
    }

    public function test_user_can_delete_certification()
    {
        $certification = GuardCertification::create([
            'guard_profile_id' => $this->profile->id,
            'name' => 'To Be Deleted',
            'issuing_organization' => 'Issuer',
        ]);

        $response = $this->actingAs($this->user)->delete(route('applicant.profile.delete-certification', $certification));

        $response->assertRedirect();
        $this->assertDatabaseMissing('guard_certifications', ['id' => $certification->id]);
    }

    public function test_user_can_update_firearm_qualification()
    {
        $response = $this->actingAs($this->user)->patch(route('applicant.profile.update-firearm-qualification'), [
            'is_firearm_qualified' => 1,
            'firearm_type' => 'Glock 17',
            'permit_number' => 'PERMIT-999',
            'issued_at' => '2023-01-01',
            'expires_at' => '2024-01-01',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guard_firearm_qualifications', [
            'guard_profile_id' => $this->profile->id,
            'is_firearm_qualified' => true,
            'firearm_type' => 'Glock 17',
            'permit_number' => 'PERMIT-999',
        ]);
    }
}
