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
        Schema::create('article_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id'); // or permit_id if you prefer
            $table->unsignedBigInteger('user_id');
            $table->string('marks_and_number')->nullable();
            $table->integer('qty')->default(0);
            $table->text('detailed_description_of_article')->nullable();
            $table->decimal('gross_weight', 10, 2)->nullable();
            $table->timestamps();

            // Foreign key relations
            $table->foreign('form_id')->references('id')->on('application_forms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_details');
    }
};
