<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugToDatabaseViewExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('database_view_exports', function (Blueprint $table) {
            $table->string('slug')->after('name');
        });

        $exports = DB::table('database_view_exports')->get();
        foreach ($exports as $export) {
            DB::table('database_view_exports')->where('id', $export->id)->update(['slug' => Str::slug($export->name)]);
        }

        Schema::table('database_view_exports', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('database_view_exports', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
