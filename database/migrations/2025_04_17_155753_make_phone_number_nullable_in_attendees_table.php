<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->string('phone_number')->nullable(false)->change();
        });
    }
    
};
