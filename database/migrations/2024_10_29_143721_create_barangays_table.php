<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('name', 255);
            $table->string('contact_person', 255);
            $table->string('phone', 255);
            $table->string('email_address', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barangays');
    }
};
