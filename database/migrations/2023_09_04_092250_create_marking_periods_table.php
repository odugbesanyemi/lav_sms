<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkingPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marking_periods', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('acad_year_id');
            $table->string('mp_type');
            $table->string('title');
            $table->string('short_name');
            $table->integer('parent_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('post_start_date')->nullable();
            $table->date('post_end_date')->nullable();
            $table->integer('does_grades');
            $table->integer('does_exams');
            $table->integer('does_comments');
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
        Schema::dropIfExists('marking_periods');
    }
}
