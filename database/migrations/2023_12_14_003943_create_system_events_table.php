<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('month');
            $table->unsignedTinyInteger('day');
            $table->enum('type', ['name day', 'birthday', 'holiday', 'anniversary', 'other'])->default('name day');
            $table->enum('interval', ['yearly', 'monthly', 'biweekly', 'weekly', 'daily', 'none'])->default('yearly');
            $table->boolean("isCustom")->default(false);
            $table->time('hour')->default('00:00')->nullable();;
            $table->string('notification_message')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('system_events');
    }
}
