<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('resource_type_id');
            $table->string('description')->nullable();
            $table->integer('school_id');
            $table->integer('acad_year_id');
            $table->integer('subject_id');
            $table->integer('added_by');
            $table->string('filename');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('resource');
    }
}
