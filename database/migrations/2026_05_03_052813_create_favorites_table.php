<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id('id_favourite');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('id_recipe');
            $table->foreign('id_recipe')
                  ->references('id_recipe')
                  ->on('recipes')
                  ->onDelete('cascade');

            $table->timestamp('date_added')->useCurrent();
            $table->text('notes')->nullable();

            $table->unique(['id_user', 'id_recipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};