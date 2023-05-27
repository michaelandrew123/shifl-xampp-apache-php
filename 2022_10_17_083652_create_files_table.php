<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
//            $table->id();
//            $table->timestamps();

            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('machine_id')->nullable();

            $table->date('date')->nullable();
            $table->string('title')->nullable();

            $table->string('key')->nullable(); // this is the identifier of the specific file, like an id.

            $table->text('description')->nullable();

            $table->text('position')->nullable();

            $table->string('src')->nullable();
            $table->string('path')->nullable();
            $table->string('mime')->nullable();
            $table->string('size')->nullable();
            $table->string('size_type')->nullable(); // small, medium, large, etc..
            $table->string('real_path')->nullable();
            $table->string('name')->nullable();
            $table->string('new_name')->nullable();

            $table->integer('fileable_id')->nullable();
            $table->string('fileable_type')->nullable();

            $table->string('type')->nullable(); // file

            $table->tinyInteger('available')->default(0); // this can be available to someone
            $table->integer('order')->nullable();


            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('files');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
