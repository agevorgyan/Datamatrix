<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LabelSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo user & settings.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@elab.am'],
            [
                'name' => 'Արմեն Պետրոսյան',
                'password' => Hash::make('password'),
            ]
        );

        LabelSetting::updateOrCreate(
            ['user_id' => $user->id, 'is_default' => true],
            [
                'setting_name' => 'XP-356B (20x30մմ)',
                'width_mm' => 20.0,
                'height_mm' => 30.0,
                'margin_mm' => 1.0,
                'orientation' => 'portrait',
                'product_font_size' => 9,
                'product_font_bold' => true,
                'product_pos_x' => 1.5,
                'product_pos_y' => 2.0,
                'last5_font_size' => 11,
                'last5_font_bold' => true,
                'last5_pos_x' => 1.5,
                'last5_pos_y' => 7.0,
                'datamatrix_size' => 15.0,
                'datamatrix_pos_x' => 2.5,
                'datamatrix_pos_y' => 12.0,
            ]
        );
    }
}
