<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('database_view_exports', function (Blueprint $table) {
            $table->string('slug')->after('id');
        });

        DB::table('database_view_exports')
            ->orderBy('id')
            ->each(fn($export) => DB::table('database_view_exports')->where('id', $export->id)->update(['slug' => Str::slug($export->name)]));

        Schema::table('database_view_exports', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('database_view_exports', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
