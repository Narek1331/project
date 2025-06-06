<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('domain');
            $table->string('region');
            $table->bigInteger('click_in_hour')->nullable();
            $table->bigInteger('click_per_day')->nullable();
            $table->bigInteger('clean_click_in_hour')->nullable();
            $table->bigInteger('clean_click_per_day')->nullable();
            $table->boolean('status')->nullable()->default(false);
            $table->string('password')->nullable();
            $table->timestamps();

            // $table->foreign('user_id')
            //     ->references('id')
            //     ->on('users')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
