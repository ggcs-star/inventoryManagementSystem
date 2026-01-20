<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'last_login_user_agent')) {
                $table->text('last_login_user_agent')
                      ->nullable()
                      ->after('last_login_ip');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'last_login_user_agent')) {
                $table->dropColumn('last_login_user_agent');
            }
        });
    }
};
