<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('label_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('label_settings', 'margin_top_mm')) {
                $table->float('margin_top_mm')->default(0.0)->after('margin_mm');
                $table->float('margin_bottom_mm')->default(0.0)->after('margin_top_mm');
                $table->float('margin_left_mm')->default(0.0)->after('margin_bottom_mm');
                $table->float('margin_right_mm')->default(0.0)->after('margin_left_mm');
                $table->float('label_gap_mm')->default(2.0)->after('margin_right_mm');
                $table->integer('print_scale')->default(100)->after('label_gap_mm');
                $table->integer('print_dpi')->default(203)->after('print_scale');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('label_settings', function (Blueprint $table) {
            $table->dropColumn([
                'margin_top_mm',
                'margin_bottom_mm',
                'margin_left_mm',
                'margin_right_mm',
                'label_gap_mm',
                'print_scale',
                'print_dpi',
            ]);
        });
    }
};
