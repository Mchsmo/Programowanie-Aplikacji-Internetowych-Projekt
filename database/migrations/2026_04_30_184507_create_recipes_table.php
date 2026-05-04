<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id('id_recipe');
            $table->string('title', 150);
            $table->text('description');
            $table->integer('prep_time');
            $table->integer('calories')->nullable();
            $table->string('image_path')->nullable();

            $table->unsignedBigInteger('id_category');
            $table->foreign('id_category')
                  ->references('id_category')
                  ->on('categories')
                  ->onDelete('restrict');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->boolean('is_visible')->default(true);
            $table->timestamps(); // date_created + date_modified
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};