<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_periods', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('acad_year_id');
            $table->string('title');
            $table->string('short_name');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('length');
            $table->integer('used_for_attendance');
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
        Schema::dropIfExists('class_periods');
    }
}
