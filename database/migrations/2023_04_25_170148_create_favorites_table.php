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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fav_user_id');
            $table->unsignedBigInteger('fav_post_id');
            $table->timestamps();
            
            $table->foreign('fav_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fav_post_id')->references('id')->on('microposts')->onDelete('cascade');
        
            
            $table->unique(['fav_user_id', 'fav_post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};
