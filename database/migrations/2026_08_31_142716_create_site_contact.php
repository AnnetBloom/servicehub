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
        Schema::create('site_contact', function (Blueprint $table) {
            $table->foreignId('site_id')
                ->constrained('sites')
                ->cascadeOnDelete();
                
            $table->foreignId('contact_id')
                ->constrained('contacts')
                ->cascadeOnDelete();

            $table->unique(['site_id', 'contact_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_contact');
    }
};
