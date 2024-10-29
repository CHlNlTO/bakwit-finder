<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evacuee_family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evacuee_id')->constrained()->onDelete('cascade');
            $table->string('last_name', 255);
            $table->string('first_name', 255);
            $table->string('middle_name', 255);
            $table->date('birthday');
            $table->integer('age');
            $table->string('relationship', 255);
            $table->string('religion', 255);
            $table->string('nationality', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evacuee_family_members');
    }
};
