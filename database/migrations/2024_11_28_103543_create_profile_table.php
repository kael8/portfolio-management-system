<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Associates the profile with a user
            $table->string('title')->nullable(); // Title or designation
            $table->text('description')->nullable(); // Profile description
            $table->string('phone')->nullable(); // Phone number
            $table->string('address')->nullable(); // Physical address
            $table->string('facebook')->nullable(); // Facebook profile link
            $table->string('twitter')->nullable(); // Twitter profile link
            $table->string('instagram')->nullable(); // Instagram profile link
            $table->string('linkedin')->nullable(); // LinkedIn profile link
            $table->timestamps(); // Created and updated timestamps

            // Foreign key constraint to ensure user_id exists in the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile');
    }
};
