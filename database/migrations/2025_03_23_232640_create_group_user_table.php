<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_group_user_table.php

public function up()
{
    Schema::create('group_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('group_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    
     public function down()
     {
         Schema::dropIfExists('group_user');
     }
};