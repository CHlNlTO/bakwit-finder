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
            $table->foreignId('family_id')->constrained()->onDelete('cascade');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
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
