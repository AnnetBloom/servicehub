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
        Schema::create('device_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->restrictOnDelete();
            $table->enum('event_type', ['installed', 'moved', 'sent_to_repair', 'returned_from_repair', 'removed', 'written_off']);
            $table->foreignId('old_site_id')->nullable()->constrained('sites')->restrictOnDelete();
            $table->foreignId('new_site_id')->nullable()->constrained('sites')->restrictOnDelete();
            $table->foreignId('changed_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('event_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_history');
    }
};
