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
        Schema::create('print_job_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('print_job_id')->constrained()->onDelete('cascade');
            $table->text('code');
            $table->string('last_5_chars', 10);
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_job_codes');
    }
};
