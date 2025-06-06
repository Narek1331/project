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
        Schema::create('site_keywords', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->string('name');
            $table->string('url')->nullable();
            $table->bigInteger('clicks_per_day')->default(0);
            $table->boolean('status')->nullable()->default(false);
            $table->timestamps();

             $table->foreign('site_id')
            ->references('id')
            ->on('sites')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siet_keywords');
    }
};
