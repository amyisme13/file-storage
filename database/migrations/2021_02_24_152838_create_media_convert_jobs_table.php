<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaConvertJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_convert_jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('job_id');
            $table->string('arn');
            $table->string('queue');
            $table->string('status');
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
        Schema::dropIfExists('media_convert_jobs');
    }
}
