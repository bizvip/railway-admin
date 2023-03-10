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
        Schema::create('app', function (Blueprint $table) {
            $table->increments('id')->from(10000);
            $table->string('name', 32)->unique()->default('')->comment('名称');
            $table->string('category_ids')->comment('分类')->nullable(false);
            $table->string('img', 200)->default('')->comment('图片');
            $table->char('slug', 36)->unique()->comment('唯一标识');
            $table->string('link', 200)->nullable(false)->comment('链接');
            $table->unsignedInteger('weight')->default(0)->comment('升序权重');
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
        Schema::dropIfExists('app');
    }
};
