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
        Schema::create("resources", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->enum("category", ["Server", "VM", "Storage", "Network"]);
            $table->json("specs"); // Stores CPU, RAM, etc.
            $table->enum("status", ["Enabled", "Disabled"])->default("Enabled");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("resources");
    }
};
