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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->restrictOnDelete();
            $table->foreignId('contract_id')->constrained('contracts')->restrictOnDelete();
            $table->foreignId('device_id')->nullable()->constrained('devices')->restrictOnDelete();
            $table->text('text_issue'); 
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->restrictOnDelete();
            $table->enum('status', [
                'created',
                'in_progress',
                'declined', 
                'resolved', 
                'closed', 
                'reopened'
            ])->default('created');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
