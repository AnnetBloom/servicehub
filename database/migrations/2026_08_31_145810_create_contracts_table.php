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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('currency', 3);
            $table->enum('billing_period', ['monthly', 'quarterly', 'yearly', 'one_time']);
            $table->enum('status', ['draft', 'active', 'expired', 'terminated']);
            $table->string('file')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
