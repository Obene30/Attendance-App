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
        Schema::create('visitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendee_id')->constrained()->onDelete('cascade');
            $table->foreignId('shepherd_id')->constrained('users')->onDelete('cascade');
            $table->text('admin_comment')->nullable();
            $table->text('shepherd_comment')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitations');
    }
};
