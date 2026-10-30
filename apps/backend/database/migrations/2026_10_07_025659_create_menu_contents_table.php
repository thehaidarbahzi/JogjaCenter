<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("menu_contents", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->string("superset_id", 36);
            $table
                ->foreignId("menu_id")
                ->constrained("menus")
                ->restrictOnDelete();
            $table->enum("visibility", ["public", "private", "off"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("menu_contents");
    }
};
