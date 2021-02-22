<?php

use App\Enums\FileStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->string('type');
            $table->bigInteger('size');

            $table->string('slug');

            $table->string('path')->nullable();
            $table->string('thumbnail')->nullable();

            $table->string('job_id')->nullable()->comment('AWS MediaConvert job id');

            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('processed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
