<?php

namespace Tests\Feature;

use App\Models\PrintJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GuestModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_guest_can_access_dashboard_homepage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Դատամատրիքսի գեներացման և տպագրության համակարգ');
        $response->assertSee('Հյուրի ռեժիմ');
    }

    public function test_unauthenticated_guest_can_preview_csv()
    {
        $file = UploadedFile::fake()->createWithContent('guest_preview.csv', "010486000543210921GUEST1\n010486000543210921GUEST2\n");

        $response = $this->postJson(route('dashboard.preview-csv'), [
            'csv_file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('total_count', 2);
    }

    public function test_unauthenticated_guest_can_store_batch_without_database_history()
    {
        $file = UploadedFile::fake()->createWithContent('guest_batch.csv', "010486000543210921GUEST1\n010486000543210921GUEST2\n");

        $response = $this->postJson(route('dashboard.store-batch'), [
            'csv_file' => $file,
            'product_name' => 'Guest Product Batch',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('redirect_url', route('dashboard.print-guest'));

        // Assert NO history record created in DB for guest
        $this->assertEquals(0, PrintJob::count());
    }

    public function test_unauthenticated_guest_can_render_print_sheet_from_session()
    {
        $this->withSession([
            'guest_print_job' => [
                'product_name' => 'Guest Juice Batch',
                'file_name' => 'juice.csv',
                'total_codes' => 1,
                'printed_count' => 1,
                'codes' => [
                    (object) ['code' => '010486000543210921ABCDE', 'last_5_chars' => 'ABCDE'],
                ],
            ]
        ]);

        $response = $this->get(route('dashboard.print-guest'));

        $response->assertStatus(200);
        $response->assertSee('Guest Juice Batch');
        $response->assertSee('ABCDE');
    }

    public function test_unauthenticated_guest_can_render_print_sheet_with_array_codes()
    {
        $this->withSession([
            'guest_print_job' => [
                'product_name' => 'Array Format Batch',
                'file_name' => 'array.csv',
                'total_codes' => 1,
                'printed_count' => 1,
                'codes' => [
                    ['code' => '010486000543210921XYZ99', 'last_5_chars' => 'XYZ99'],
                ],
            ]
        ]);

        $response = $this->get(route('dashboard.print-guest'));

        $response->assertStatus(200);
        $response->assertSee('Array Format Batch');
        $response->assertSee('XYZ99');
    }

    public function test_unauthenticated_guest_can_access_and_save_label_settings_in_session()
    {
        $getRes = $this->get(route('settings.edit'));
        $getRes->assertStatus(200);

        $postRes = $this->postJson(route('settings.update'), [
            'setting_name' => 'Guest Custom 30x40',
            'width_mm' => 30.0,
            'height_mm' => 40.0,
            'margin_mm' => 1.5,
            'orientation' => 'portrait',
            'product_font_size' => 10,
            'product_font_bold' => true,
            'product_pos_x' => 2.0,
            'product_pos_y' => 3.0,
            'last5_font_size' => 12,
            'last5_font_bold' => true,
            'last5_pos_x' => 2.0,
            'last5_pos_y' => 8.0,
            'datamatrix_size' => 18.0,
            'datamatrix_pos_x' => 3.0,
            'datamatrix_pos_y' => 15.0,
        ]);

        $postRes->assertStatus(200);
        $postRes->assertJsonPath('success', true);
        $this->assertEquals(30.0, session('guest_label_setting.width_mm'));
    }

    public function test_unauthenticated_guest_cannot_access_history_page()
    {
        $response = $this->get(route('history.index'));

        $response->assertRedirect(route('login'));
    }
}
