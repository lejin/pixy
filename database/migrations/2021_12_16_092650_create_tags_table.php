<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('image_id');
            $table->integer('a_x');
            $table->integer('a_y');
            $table->integer('b_x');
            $table->integer('b_y');
            $table->integer('c_x');
            $table->integer('c_y');
            $table->integer('d_x');
            $table->integer('d_y');
            $table->string('label');
            $table->json('info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
