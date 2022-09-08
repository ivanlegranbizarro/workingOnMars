<?php

use App\Models\Company;
use App\Models\Application;
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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->mediumText('description');
            $table->mediumText('searching_for');
            $table->enum('type_of_job', ['full_time', 'part_time', 'internship']);
            $table->enum('modality', ['remote', 'office', 'both']);
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete();
            $table->enum('planet', ['earth', 'mars'])->default('earth');
            $table->string('city')->nullable(false);
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
        Schema::dropIfExists('jobs');
    }
};
