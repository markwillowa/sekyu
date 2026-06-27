<?php

namespace Tests\Feature;

use App\Models\GuardClearance;
use App\Models\GuardIdentification;
use App\Models\GuardMedical;
use App\Models\MasterClearanceType;
use App\Models\MasterIdentificationType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->user->assignRole('applicant');
        $this->user->guardProfile()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $this->actingAs($this->user);
    }

    public function test_user_can_add_clearance()
    {
        $type = MasterClearanceType::create(['name' => 'NBI Clearance', 'code' => 'NBI']);

        $response = $this->post(route('applicant.profile.store-clearance'), [
            'master_clearance_type_id' => $type->id,
            'clearance_number' => '12345',
            'issuing_office' => 'Manila',
            'issued_at' => '2023-01-01',
            'expires_at' => '2024-01-01',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guard_clearances', [
            'guard_profile_id' => $this->user->guardProfile->id,
            'clearance_number' => '12345',
        ]);
    }

    public function test_user_can_update_clearance()
    {
        $type = MasterClearanceType::create(['name' => 'NBI Clearance', 'code' => 'NBI']);
        $clearance = $this->user->guardProfile->clearances()->create([
            'master_clearance_type_id' => $type->id,
            'clearance_number' => 'OLD123',
        ]);

        $response = $this->patch(route('applicant.profile.update-clearance', $clearance), [
            'master_clearance_type_id' => $type->id,
            'clearance_number' => 'NEW123',
        ]);

        $response->assertRedirect();
        $this->assertEquals('NEW123', $clearance->fresh()->clearance_number);
    }

    public function test_user_can_delete_clearance()
    {
        $type = MasterClearanceType::create(['name' => 'NBI Clearance', 'code' => 'NBI']);
        $clearance = $this->user->guardProfile->clearances()->create([
            'master_clearance_type_id' => $type->id,
        ]);

        $response = $this->delete(route('applicant.profile.delete-clearance', $clearance));

        $response->assertRedirect();
        $this->assertDatabaseMissing('guard_clearances', ['id' => $clearance->id]);
    }

    public function test_user_can_add_medical_record()
    {
        $response = $this->post(route('applicant.profile.store-medical'), [
            'certificate_type' => 'Medical Certificate',
            'clinic_or_hospital' => 'City Hospital',
            'physician_name' => 'Dr. Smith',
            'issued_at' => '2023-01-01',
            'expires_at' => '2023-12-31',
            'is_fit_to_work' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guard_medicals', [
            'guard_profile_id' => $this->user->guardProfile->id,
            'certificate_type' => 'Medical Certificate',
        ]);
    }

    public function test_user_can_update_medical_record()
    {
        $medical = $this->user->guardProfile->medicals()->create([
            'certificate_type' => 'Old Type',
        ]);

        $response = $this->patch(route('applicant.profile.update-medical', $medical), [
            'certificate_type' => 'New Type',
        ]);

        $response->assertRedirect();
        $this->assertEquals('New Type', $medical->fresh()->certificate_type);
    }

    public function test_user_can_delete_medical_record()
    {
        $medical = $this->user->guardProfile->medicals()->create([
            'certificate_type' => 'To Delete',
        ]);

        $response = $this->delete(route('applicant.profile.delete-medical', $medical));

        $response->assertRedirect();
        $this->assertDatabaseMissing('guard_medicals', ['id' => $medical->id]);
    }

    public function test_user_can_add_identification()
    {
        $type = MasterIdentificationType::create(['name' => 'Passport', 'code' => 'passport']);

        $response = $this->post(route('applicant.profile.store-identification'), [
            'master_identification_type_id' => $type->id,
            'id_number' => 'P1234567',
            'issuing_authority' => 'DFA',
            'issued_at' => '2020-01-01',
            'expires_at' => '2030-01-01',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guard_identifications', [
            'guard_profile_id' => $this->user->guardProfile->id,
            'master_identification_type_id' => $type->id,
            'id_number' => 'P1234567',
        ]);
    }

    public function test_user_can_update_identification()
    {
        $type = MasterIdentificationType::create(['name' => 'Passport', 'code' => 'passport']);
        $id = $this->user->guardProfile->identifications()->create([
            'master_identification_type_id' => $type->id,
            'id_number' => '123',
        ]);

        $response = $this->patch(route('applicant.profile.update-identification', $id), [
            'master_identification_type_id' => $type->id,
            'id_number' => '456',
        ]);

        $response->assertRedirect();
        $this->assertEquals('456', $id->fresh()->id_number);
    }

    public function test_user_can_delete_identification()
    {
        $id = $this->user->guardProfile->identifications()->create([
            'id_type' => 'To Delete',
            'id_number' => '123',
        ]);

        $response = $this->delete(route('applicant.profile.delete-identification', $id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('guard_identifications', ['id' => $id->id]);
    }
}
