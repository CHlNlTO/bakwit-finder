<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evacuation_center_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->integer('quantity');
            $table->longText('description');
            $table->string('donated_by', 255);
            $table->string('donated_by_contact', 255);
            $table->string('received_by', 255);
            $table->dateTime('received_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};
