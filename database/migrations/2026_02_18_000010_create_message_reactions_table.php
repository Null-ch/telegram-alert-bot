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
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->string('chat_title')->nullable();
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('reaction')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['chat_id', 'message_id', 'employee_id'], 'message_reactions_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_reactions');
    }
};


