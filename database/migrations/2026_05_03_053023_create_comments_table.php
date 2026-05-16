<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id_comment');

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

            $table->text('content');

            $table->timestamp('date_added')->useCurrent();
            $table->timestamp('date_modified')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};