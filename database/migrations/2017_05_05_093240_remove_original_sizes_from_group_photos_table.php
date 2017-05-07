<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOriginalSizesFromGroupPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_photos', function (Blueprint $table) {
            $table->unique('hash');
            $table->dropColumn(['original_width', 'original_height']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_photos', function (Blueprint $table) {
            $table->dropUnique('group_photos_hash_unique');
            $table->smallInteger('original_width');
            $table->smallInteger('original_height');
        });
    }
}
