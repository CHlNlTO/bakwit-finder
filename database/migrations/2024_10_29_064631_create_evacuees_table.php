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
        Schema::create('evacuees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade');
            $table->foreignId('evac_center_id')->constrained()->onDelete('cascade');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('gender');
            $table->date('birth_date');
            $table->integer('age');
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->string('address');
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('sector')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evacuees');
    }
};
