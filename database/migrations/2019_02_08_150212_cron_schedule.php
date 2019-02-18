<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CronSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_schedule', function (Blueprint $table) {
            $table->increments('schedule_id');
            $table->string('command', 50);
            $table->string('status', 25);
            $table->string('message')->nullable(true);
            $table->timestamp('scheduled_at');
            $table->timestamp('executed_at')->nullable(true);
            $table->timestamp('finished_at')->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
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
        Schema::dropIfExists('cron_schedule');
    }
}
