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
        Schema::create('ticket_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->enum('status', [
                'created',
                'in_progress',
                'declined', 
                'resolved', 
                'closed', 
                'reopened'
            ]);
            $table->foreignId('changed_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('changed_at')->useCurrent();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_status_history');
    }
};
