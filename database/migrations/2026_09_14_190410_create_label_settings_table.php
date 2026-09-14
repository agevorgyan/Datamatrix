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
        Schema::create('label_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('setting_name')->default('Default XP-356B 20x30mm');
            $table->float('width_mm')->default(20.0);
            $table->float('height_mm')->default(30.0);
            $table->float('margin_mm')->default(1.0);
            $table->string('orientation')->default('portrait');
            
            // Product Name Label Settings
            $table->integer('product_font_size')->default(9);
            $table->boolean('product_font_bold')->default(true);
            $table->float('product_pos_x')->default(1.5);
            $table->float('product_pos_y')->default(2.0);
            
            // Last 5 Characters Settings
            $table->integer('last5_font_size')->default(11);
            $table->boolean('last5_font_bold')->default(true);
            $table->float('last5_pos_x')->default(1.5);
            $table->float('last5_pos_y')->default(7.0);
            
            // DataMatrix Settings
            $table->float('datamatrix_size')->default(15.0);
            $table->float('datamatrix_pos_x')->default(2.5);
            $table->float('datamatrix_pos_y')->default(12.0);
            
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_settings');
    }
};
