<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evacuation_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade');
            $table->string('name', 255);
            $table->string('address', 255);
            $table->decimal('longitude', 9, 6);
            $table->decimal('latitude', 9, 6);
            $table->integer('capacity');
            $table->string('contact_person', 255);
            $table->string('contact_number', 255);
            $table->longText('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evacuation_centers');
    }
};
