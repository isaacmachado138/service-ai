<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->text('prompt');
            $table->longText('response');
            $table->float('execution_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_logs');
    }
};
