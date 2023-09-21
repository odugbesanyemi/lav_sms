<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('email');
            $table->string('generic_name');
            $table->string('principal')->nullable();
            $table->string('phone');
            $table->string('telephone')->nullable();
            $table->integer('nationality')->nullable();
            $table->integer('state')->nullable();
            $table->integer('lga')->nullable();
            $table->string('logo')->nullable();
            $table->integer('active')->nullable();
            $table->integer('maintenance')->nullable();
            $table->string('maintenance_msg')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
