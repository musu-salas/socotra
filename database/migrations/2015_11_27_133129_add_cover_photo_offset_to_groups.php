<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverPhotoOffsetToGroups extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('groups', function (Blueprint $table) {
            $table->integer('cover_photo_id')->unsigned()->nullable()->change();
            $table->foreign('cover_photo_id')->references('id')->on('group_photos');

            $table->tinyInteger('cover_photo_offset')->default(0)->after('cover_photo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_cover_photo_id_foreign');
            $table->integer('cover_photo_id')->nullable()->change();

            $table->dropColumn('cover_photo_offset');
        });
    }
}
