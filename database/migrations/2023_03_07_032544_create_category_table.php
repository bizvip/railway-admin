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
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id')->from(10000);
            $table->string('name', 20)->unique()->default('');
            $table->string('slug', 32)->unique()->default('');
            $table->unsignedInteger('weight')->default(new \Illuminate\Database\Query\Expression('0'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
