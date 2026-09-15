<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PrintJob;
use App\Models\PrintJobCode;
use App\Models\LabelSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DataMatrixAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@elab.am',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        // Default LabelSetting should be automatically generated for the user
        $this->assertDatabaseHas('label_settings', [
            'user_id' => auth()->id(),
            'setting_name' => 'XP-356B (20x30մմ)',
        ]);
    }

    public function test_user_can_register_with_phone_and_marketing_consent()
    {
        $response = $this->post('/register', [
            'name' => 'Armen Petrosyan',
            'email' => 'armen@elab.am',
            'phone' => '+374 99 123456',
            'marketing_consent' => '1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'armen@elab.am',
            'phone' => '+374 99 123456',
            'marketing_consent' => true,
        ]);
    }

    public function test_csv_preview_returns_first_5_rows()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $csvContent = "010486000543210921CODE1\n010486000543210921CODE2\n010486000543210921CODE3\n010486000543210921CODE4\n010486000543210921CODE5\n010486000543210921CODE6\n";
        $file = UploadedFile::fake()->createWithContent('codes.csv', $csvContent);

        $response = $this->postJson(route('dashboard.preview-csv'), [
            'csv_file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total_count' => 6,
        ]);

        $response->assertJsonCount(5, 'preview_rows');
        $response->assertJsonPath('preview_rows.0.last_5', 'CODE1');
    }

    public function test_store_batch_creates_print_job_and_codes()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $csvContent = "010486000543210921CODE1\n010486000543210921CODE2\n";
        $file = UploadedFile::fake()->createWithContent('test_batch.csv', $csvContent);

        $response = $this->postJson(route('dashboard.store-batch'), [
            'csv_file' => $file,
            'product_name' => 'Թեստային Ապրանք',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('print_jobs', [
            'user_id' => $user->id,
            'product_name' => 'Թեստային Ապրանք',
            'total_codes' => 2,
            'status' => 'completed',
        ]);
    }

    public function test_batch_deletion_in_history()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $job1 = PrintJob::create([
            'user_id' => $user->id,
            'product_name' => 'Job 1',
            'file_name' => 'f1.csv',
            'total_codes' => 10,
            'status' => 'completed',
        ]);

        $job2 = PrintJob::create([
            'user_id' => $user->id,
            'product_name' => 'Job 2',
            'file_name' => 'f2.csv',
            'total_codes' => 5,
            'status' => 'pending',
        ]);

        $response = $this->post(route('history.delete-batch'), [
            'ids' => [$job1->id, $job2->id],
        ]);

        $response->assertRedirect(route('history.index'));
        $this->assertDatabaseMissing('print_jobs', ['id' => $job1->id]);
        $this->assertDatabaseMissing('print_jobs', ['id' => $job2->id]);
    }

    public function test_export_history_as_csv()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        PrintJob::create([
            'user_id' => $user->id,
            'product_name' => 'Export Product',
            'file_name' => 'export.csv',
            'total_codes' => 20,
            'status' => 'completed',
        ]);

        $response = $this->get(route('history.export-csv'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_legal_pages_render()
    {
        $this->get(route('terms'))->assertStatus(200);
        $this->get(route('privacy'))->assertStatus(200);
    }
}
