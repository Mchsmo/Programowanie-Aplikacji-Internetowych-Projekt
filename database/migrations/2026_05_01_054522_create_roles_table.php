<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
	{
		Schema::create('roles', function (Blueprint $table) {
			$table->id(); // Odpowiednik id_role
			$table->string('name', 50)->unique(); // np. admin, moderator, użytkownik
			$table->boolean('is_active')->default(true);
			$table->timestamps(); // date_created z diagramu
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
