<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('telegram_id')->unique()->nullable();
            $table->boolean('is_admin')->default(false);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('sex')->nullable();
            $table->string('email')->unique()->nullable();
            $table->unsignedInteger('weight')->nullable();
            $table->unsignedInteger('growth')->nullable();
            $table->string('phone')->nullable();
            $table->text('allergy')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default(bcrypt(str_random(32)));
            $table->string('avatar_url')->nullable();
            $table->string('confirm_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
