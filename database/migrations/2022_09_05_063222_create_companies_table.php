<?php

use App\Models\Job;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->mediumText('description');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('planet', ['earth', 'mars'])->default('earth');
            $table->string('street')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('zip')->nullable(false);
            $table->string('state')->nullable(false);
            $table->string('password');
            $table->enum('role', ['company', 'candidate'])->default('company');
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
        Schema::dropIfExists('companies');
    }
};
