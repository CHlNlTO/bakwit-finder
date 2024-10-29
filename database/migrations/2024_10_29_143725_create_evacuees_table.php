<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evacuees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade');
            $table->foreignId('evacuation_center_id')->constrained()->onDelete('cascade');
            $table->string('last_name', 255);
            $table->string('first_name', 255);
            $table->string('middle_name', 255);
            $table->string('gender', 50);
            $table->date('birthday');
            $table->integer('age');
            $table->string('religion', 255);
            $table->string('nationality', 255);
            $table->string('house_no', 255);
            $table->string('lot', 255);
            $table->string('block', 255);
            $table->string('subdivision', 255);
            $table->string('street', 255);
            $table->longText('address');
            $table->string('landmark', 255);
            $table->string('contact_no', 255);
            $table->string('email_address', 255);
            $table->string('sector', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evacuees');
    }
};
