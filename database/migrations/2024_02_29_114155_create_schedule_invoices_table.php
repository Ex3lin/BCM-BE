<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_invoices', function (Blueprint $table) {
            $table->id();
            $table->timestamp('deadline')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('type',['income', 'expense'])
                ->default('income');
            $table->enum('loop',['daily','weekly','tenDays','monthly','yearly'])
                ->default('monthly');
            $table->unsignedInteger('duration')
                ->default(0);
            $table->unsignedInteger('cost');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_invoices');
    }
};
