<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('database_view_exports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('view_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('database_view_exports');
    }
};
