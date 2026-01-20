<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'last_login_country')) {
                $table->string('last_login_country')->nullable()
                      ->after('last_login_user_agent');
            }

            if (!Schema::hasColumn('users', 'last_login_city')) {
                $table->string('last_login_city')->nullable()
                      ->after('last_login_country');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_country',
                'last_login_city',
            ]);
        });
    }
};
