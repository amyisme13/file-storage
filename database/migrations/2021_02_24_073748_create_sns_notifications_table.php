<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSnsNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sns_notifications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('message_id');
            $table->text('message');
            $table->string('topic_arn');
            $table->timestamp('message_timestamp');
            $table->text('raw');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sns_notifications');
    }
}
