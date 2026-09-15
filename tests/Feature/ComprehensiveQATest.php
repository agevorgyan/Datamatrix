<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PrintJob;
use App\Models\PrintJobCode;
use App\Models\LabelSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ComprehensiveQATest extends TestCase
{
    use RefreshDatabase;

    /** ----------------------------------------------------
     * 1. AUTHENTICATION & SECURITY QA TESTS
     * ---------------------------------------------------- */

    public function test_registration_validation_fails_on_short_password()
    {
        $response = $this->post('/register', [
            'name' => 'QA Tester',
            'email' => 'qa@elab.am',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_registration_fails_on_duplicate_email()
    {
        User::factory()->create(['email' => 'existing@elab.am']);

        $response = $this->post('/register', [
            'name' => 'New QA User',
            'email' => 'existing@elab.am',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_registration_sends_email_notification_to_datamatrix_elab_am()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Notification User',
            'email' => 'notify@elab.am',
            'phone' => '+37499112233',
            'marketing_consent' => '1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\UserRegistrationMail::class, function ($mail) {
            return $mail->hasTo('datamatrix@elab.am') &&
                   $mail->user->email === 'notify@elab.am' &&
                   $mail->user->phone === '+37499112233' &&
                   $mail->user->marketing_consent === true;
        });
    }

    public function test_password_change_sends_email_notification_to_datamatrix_elab_am()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $user = User::factory()->create([
            'email' => 'passchange@elab.am',
            'password' => \Illuminate\Support\Facades\Hash::make('oldpassword123'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/change-password', [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $user->fresh()->password));

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\PasswordChangedMail::class, function ($mail) {
            return $mail->hasTo('datamatrix@elab.am') &&
                   $mail->user->email === 'passchange@elab.am';
        });
    }

    public function test_user_data_isolation_security()
    {
        $userA = User::factory()->create(['email' => 'usera@elab.am']);
        $userB = User::factory()->create(['email' => 'userb@elab.am']);

        $jobA = PrintJob::create([
            'user_id' => $userA->id,
            'product_name' => 'User A Secret Product',
            'file_name' => 'a.csv',
            'total_codes' => 5,
            'status' => 'completed',
        ]);

        // User B tries to view User A's job details -> Should get 403 Forbidden
        $this->actingAs($userB);
        $response = $this->get(route('history.show', $jobA->id));
        $response->assertStatus(403);

        // User B tries to print User A's job -> Should get 403 Forbidden
        $responsePrint = $this->get(route('dashboard.print', $jobA->id));
        $responsePrint->assertStatus(403);
    }

    /** ----------------------------------------------------
     * 2. DEDICATED SETTINGS & HELP PAGES QA TESTS
     * ---------------------------------------------------- */

    public function test_settings_page_renders_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('settings.edit'));
        $response->assertStatus(200);
        $response->assertSee('Լեյբլի Ձևաչափի Կարգավորումներ');
    }

    public function test_help_page_renders_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('help.index'));
        $response->assertStatus(200);
        $response->assertSee('Օգնություն');
    }

    /** ----------------------------------------------------
     * 3. CSV IMPORT & PREVIEW ENGINE QA TESTS
     * ---------------------------------------------------- */

    public function test_csv_upload_rejects_non_csv_file_type()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $fakePdf = UploadedFile::fake()->create('malicious.pdf', 100, 'application/pdf');

        $response = $this->postJson(route('dashboard.preview-csv'), [
            'csv_file' => $fakePdf,
        ]);

        $response->assertStatus(422);
    }

    public function test_csv_upload_accepts_various_mime_types_with_csv_extension()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Excel-exported CSV mime type
        $excelCsv = UploadedFile::fake()->createWithContent('data.csv', "0104600000000000215XXXXX\n", 'application/vnd.ms-excel');

        $response = $this->postJson(route('dashboard.preview-csv'), [
            'csv_file' => $excelCsv,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_csv_preview_handles_codes_shorter_than_5_chars()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $csvContent = "ABC\nXY\n123456789\n";
        $file = UploadedFile::fake()->createWithContent('short_codes.csv', $csvContent);

        $response = $this->postJson(route('dashboard.preview-csv'), [
            'csv_file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('preview_rows.0.last_5', 'ABC');
        $response->assertJsonPath('preview_rows.1.last_5', 'XY');
        $response->assertJsonPath('preview_rows.2.last_5', '56789');
    }

    public function test_store_batch_rejects_empty_csv()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->createWithContent('empty.csv', "\n\n");

        $response = $this->postJson(route('dashboard.store-batch'), [
            'csv_file' => $file,
            'product_name' => 'Empty Batch',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    /** ----------------------------------------------------
     * 4. LABEL SETTINGS CUSTOMIZER QA TESTS
     * ---------------------------------------------------- */

    public function test_user_can_update_custom_label_settings()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('settings.update'), [
            'setting_name' => 'Custom 40x50mm',
            'width_mm' => 40.0,
            'height_mm' => 50.0,
            'margin_mm' => 2.0,
            'orientation' => 'portrait',
            'product_font_size' => 12,
            'product_font_bold' => true,
            'product_pos_x' => 3.0,
            'product_pos_y' => 4.0,
            'last5_font_size' => 14,
            'last5_font_bold' => true,
            'last5_pos_x' => 3.0,
            'last5_pos_y' => 10.0,
            'datamatrix_size' => 20.0,
            'datamatrix_pos_x' => 5.0,
            'datamatrix_pos_y' => 20.0,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('label_settings', [
            'user_id' => $user->id,
            'width_mm' => 40.0,
            'height_mm' => 50.0,
            'datamatrix_size' => 20.0,
        ]);
    }

    /** ----------------------------------------------------
     * 5. HISTORY, BATCH DELETE & CSV EXPORT QA TESTS
     * ---------------------------------------------------- */

    public function test_history_search_and_status_filtering()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        PrintJob::create([
            'user_id' => $user->id,
            'product_name' => 'Juice Mango',
            'file_name' => 'mango.csv',
            'total_codes' => 10,
            'status' => 'completed',
        ]);

        PrintJob::create([
            'user_id' => $user->id,
            'product_name' => 'Milk 1L',
            'file_name' => 'milk.csv',
            'total_codes' => 20,
            'status' => 'pending',
        ]);

        // Search for 'Mango'
        $res1 = $this->get(route('history.index', ['search' => 'Mango']));
        $res1->assertSee('Juice Mango');
        $res1->assertDontSee('Milk 1L');

        // Filter status 'pending'
        $res2 = $this->get(route('history.index', ['status' => 'pending']));
        $res2->assertSee('Milk 1L');
        $res2->assertDontSee('Juice Mango');
    }
}
