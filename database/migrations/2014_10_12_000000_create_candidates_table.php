<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Application;

return new class extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('planet', ['earth', 'mars'])->default('earth');
            $table->string('street')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('zip')->nullable(false);
            $table->string('state')->nullable(false);
            $table->string('phone')->nullable(false);
            $table->boolean('subscribed')->default(false)->help('If the candidate is subscribed to receive notifications about new jobs.');
            $table->string('password');
            $table->enum('role', ['company', 'candidate'])->default('candidate');
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
        Schema::dropIfExists('candidates');
    }
};