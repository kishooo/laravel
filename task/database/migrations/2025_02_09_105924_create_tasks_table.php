<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('user_id'); // Foreign key for the user
            $table->string('title'); // Task title
            $table->text('description')->nullable(); // Task description (optional)
            $table->tinyInteger('is_done')->default(0); // Task completion status (0 or 1)
            $table->softDeletes(); // Adds `deleted_at` column for soft deletes
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
            $table->tinyInteger('type')->default(0); 
            // Foreign key constraint
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
        Schema::dropIfExists('tasks');
    }
}