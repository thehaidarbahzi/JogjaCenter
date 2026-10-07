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
        Schema::create("invitations", function (Blueprint $table) {
            $table->string("id", 64);
            $table->string("email");
            $table->integer("invited_by");
            $table->integer("role_id");
            $table->timestampTz("expire_at");
            $table->timestampTz("accepted_at");
            $table->timestampsTz();

            $table->primary("id");
            $table->foreign("invited_by")->references("id")->on("users");
            $table->foreign("role_id")->references("id")->on("roles");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("invitations");
    }
};
