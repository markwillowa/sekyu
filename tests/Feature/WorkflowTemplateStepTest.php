<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use App\Models\WorkflowTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowTemplateStepTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_agency_can_add_step_to_workflow_template()
    {
        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);

        $workflowTemplate = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Default Workflow',
        ]);

        $this->actingAs($agencyUser);

        $response = $this->post(route('agency.workflow-templates.steps.store', $workflowTemplate), [
            'name' => 'Interview',
            'type' => 'interview',
            'is_terminal' => 0,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('workflow_template_steps', [
            'workflow_template_id' => $workflowTemplate->id,
            'name' => 'Interview',
            'type' => 'interview',
            'sort_order' => 1,
        ]);
    }

    public function test_agency_can_add_multiple_steps_and_they_are_ordered()
    {
        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);

        $workflowTemplate = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Default Workflow',
        ]);

        $this->actingAs($agencyUser);

        $this->post(route('agency.workflow-templates.steps.store', $workflowTemplate), [
            'name' => 'Step 1',
            'type' => 'normal',
        ]);

        $this->post(route('agency.workflow-templates.steps.store', $workflowTemplate), [
            'name' => 'Step 2',
            'type' => 'normal',
        ]);

        $this->assertDatabaseHas('workflow_template_steps', [
            'workflow_template_id' => $workflowTemplate->id,
            'name' => 'Step 1',
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('workflow_template_steps', [
            'workflow_template_id' => $workflowTemplate->id,
            'name' => 'Step 2',
            'sort_order' => 2,
        ]);
    }
    public function test_agency_cannot_add_step_with_invalid_type()
    {
        $agencyUser = User::factory()->create();
        $agencyUser->assignRole('agency');
        $agency = Agency::factory()->create(['owner_id' => $agencyUser->id]);

        $workflowTemplate = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Default Workflow',
        ]);

        $this->actingAs($agencyUser);

        $response = $this->post(route('agency.workflow-templates.steps.store', $workflowTemplate), [
            'name' => 'Invalid Step',
            'type' => 'invalid_type',
        ]);

        $response->assertSessionHasErrors(['type']);
    }
}
