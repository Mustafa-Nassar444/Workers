<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('Clients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('post_id')->constrained('Posts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->unique(['client_id','post_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_orders');
    }
};
