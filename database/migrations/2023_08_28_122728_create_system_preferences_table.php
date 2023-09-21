<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_preferences', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('maintenance_status');
            $table->string('maintenance_message')->nullable();
            $table->integer('allow_email');
            $table->string('notify_email')->nullable();
            $table->integer('half_day_minutes');
            $table->integer('full_day_minutes');
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
        Schema::dropIfExists('system_preferences');
    }
}
