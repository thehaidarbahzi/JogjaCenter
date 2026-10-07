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
        Schema::create("user_resets", function (Blueprint $table) {
            $table->string("id", 64);
            $table->integer("user_id");
            $table->integer("token", 64)->unique();
            $table->integer("code", 9)->unique();
            $table->timestampTz("expire_at");
            $table->timestampsTz();

            $table->primary("id");
            $table->foreign("user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_resets");
    }
};
