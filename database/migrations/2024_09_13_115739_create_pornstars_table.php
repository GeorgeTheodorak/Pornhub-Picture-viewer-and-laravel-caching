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
        Schema::create('pornstars', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('pornhub_id')->unique(); // New field for Pornhub ID
            $table->string('name');
            $table->string('hair_color')->nullable();
            $table->string('ethnicity')->nullable();
            $table->boolean('tattoos')->default(false);
            $table->boolean('piercings')->default(false);
            $table->integer('breast_size')->nullable();
            $table->string('breast_type')->nullable();
            $table->string('gender')->nullable();
            $table->string('orientation')->nullable();
            $table->integer('age')->nullable();

            // Stats fields
            $table->integer('subscriptions')->default(0);
            $table->integer('monthly_searches')->default(0);
            $table->integer('views')->default(0);
            $table->integer('videos_count')->default(0);
            $table->integer('premium_videos_count')->default(0);
            $table->integer('white_label_video_count')->default(0);
            $table->integer('rank')->default(0);
            $table->integer('rank_premium')->default(0);
            $table->integer('rank_wl')->default(0);

            // Additional fields
            $table->string('license')->nullable();
            $table->string('wl_status')->nullable();
            $table->json('aliases')->nullable();
            $table->string('link')->nullable();
            $table->json('thumbnails')->nullable(); // Store as JSON the latest photos fetched from API
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pornstars');
    }
};
