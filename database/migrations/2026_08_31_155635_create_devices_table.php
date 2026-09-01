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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->restrictOnDelete();
            $table->foreignId('device_model_id')->constrained('device_models')->restrictOnDelete();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->string('asset_tag')->unique()->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('installed_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'removed']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
