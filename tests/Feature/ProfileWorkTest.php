<?php

namespace Tests\Feature;

use App\Models\GuardSpecialization;
use App\Models\GuardTraining;
use App\Models\MasterSpecialization;
use App\Models\MasterTrainingType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileWorkTest extends TestCase
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

    public function test_user_can_add_training()
    {
        $type = MasterTrainingType::create(['name' => 'Security', 'code' => 'SEC']);

        $response = $this->post(route('applicant.profile.store-training'), [
            'master_training_type_id' => $type->id,
            'title' => 'Basic Security Training',
            'training_provider' => 'STI',
            'completed_at' => '2023-01-01',
            'hours' => 40,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guard_trainings', [
            'guard_profile_id' => $this->user->guardProfile->id,
            'title' => 'Basic Security Training',
        ]);
    }

    public function test_user_can_update_training()
    {
        $type = MasterTrainingType::create(['name' => 'Security', 'code' => 'SEC']);
        $training = $this->user->guardProfile->trainings()->create([
            'master_training_type_id' => $type->id,
            'title' => 'Old Title',
        ]);

        $response = $this->patch(route('applicant.profile.update-training', $training), [
            'master_training_type_id' => $type->id,
            'title' => 'New Title',
        ]);

        $response->assertRedirect();
        $this->assertEquals('New Title', $training->fresh()->title);
    }

    public function test_user_can_delete_training()
    {
        $type = MasterTrainingType::create(['name' => 'Security', 'code' => 'SEC']);
        $training = $this->user->guardProfile->trainings()->create([
            'master_training_type_id' => $type->id,
            'title' => 'To Delete',
        ]);

        $response = $this->delete(route('applicant.profile.delete-training', $training));

        $response->assertRedirect();
        $this->assertDatabaseMissing('guard_trainings', ['id' => $training->id]);
    }

    public function test_user_can_add_specialization()
    {
        $spec = MasterSpecialization::create(['name' => 'VIP Protection', 'code' => 'VIP']);

        $response = $this->post(route('applicant.profile.store-specialization'), [
            'master_specialization_id' => $spec->id,
            'years_of_experience' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guard_specializations', [
            'guard_profile_id' => $this->user->guardProfile->id,
            'master_specialization_id' => $spec->id,
        ]);
    }

    public function test_user_cannot_add_duplicate_specialization()
    {
        $spec = MasterSpecialization::create(['name' => 'VIP Protection', 'code' => 'VIP']);
        $this->user->guardProfile->specializations()->create([
            'master_specialization_id' => $spec->id,
        ]);

        $response = $this->post(route('applicant.profile.store-specialization'), [
            'master_specialization_id' => $spec->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, $this->user->guardProfile->specializations()->count());
    }

    public function test_user_can_delete_specialization()
    {
        $spec = MasterSpecialization::create(['name' => 'VIP Protection', 'code' => 'VIP']);
        $gs = $this->user->guardProfile->specializations()->create([
            'master_specialization_id' => $spec->id,
        ]);

        $response = $this->delete(route('applicant.profile.delete-specialization', $gs));

        $response->assertRedirect();
        $this->assertDatabaseMissing('guard_specializations', ['id' => $gs->id]);
    }
}
