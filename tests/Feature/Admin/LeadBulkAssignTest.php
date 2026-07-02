<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Lead;
use App\Models\Sale;
use App\Models\LeadAssign;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadBulkAssignTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_bulk_assign_leads(): void
    {
        // Bypass CSRF token check for the test
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $response = $this->post(route('admin.leads.bulk-assign'), [
            'ids' => [1, 2],
            'assigned_to' => 1,
        ]);

        $response->assertRedirect();
    }

    public function test_admin_can_bulk_assign_leads(): void
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $salesperson = Sale::create([
            'name' => 'Sales Person',
            'email' => 'sales@example.com',
            'password' => bcrypt('password'),
        ]);

        $status = Status::create([
            'name' => 'new',
            'type' => 'lead',
        ]);

        $lead1 = Lead::create([
            'contact_person' => 'John Doe',
            'status_id' => $status->id,
            'emails' => ['john@example.com'],
            'phones' => [['code_idx' => 20, 'number' => '1234567890']],
        ]);

        $lead2 = Lead::create([
            'contact_person' => 'Jane Smith',
            'status_id' => $status->id,
            'emails' => ['jane@example.com'],
            'phones' => [['code_idx' => 20, 'number' => '9876543210']],
        ]);

        // Verify initial state
        $this->assertDatabaseMissing('lead_assigns', [
            'lead_id' => $lead1->id,
            'assigned_to' => $salesperson->id,
        ]);
        $this->assertDatabaseMissing('lead_assigns', [
            'lead_id' => $lead2->id,
            'assigned_to' => $salesperson->id,
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.leads.bulk-assign'), [
            'ids' => [$lead1->id, $lead2->id],
            'assigned_to' => $salesperson->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify assignments were created
        $this->assertDatabaseHas('lead_assigns', [
            'lead_id' => $lead1->id,
            'assigned_to' => $salesperson->id,
        ]);
        $this->assertDatabaseHas('lead_assigns', [
            'lead_id' => $lead2->id,
            'assigned_to' => $salesperson->id,
        ]);
    }
}
