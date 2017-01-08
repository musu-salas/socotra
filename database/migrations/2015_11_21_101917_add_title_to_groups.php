<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleToGroups extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->after('is_public');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
}
