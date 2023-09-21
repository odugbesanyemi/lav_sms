<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mark_preferences', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('acad_year_id');
            $table->integer('marking_period_id');
            $table->integer('ca_final_score')->nullable();
            $table->integer('exam_final_score')->nullable();
            $table->integer('show_skills')->default(1);
            $table->integer('type_order')->nullable();
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
        Schema::dropIfExists('mark_preferences');
    }
}
