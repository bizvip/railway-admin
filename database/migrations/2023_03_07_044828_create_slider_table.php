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
        Schema::create('slider', function (Blueprint $table) {
            $table->increments('id')->from(10000);
            $table->string('title', 64)->unique()->default('')->comment('标题');
            $table->string('slug', 32)->unique()->default('')->comment('唯一标识');
            $table->string('img', 200)->default('')->comment('图片地址');
            $table->string('link', 200)->default('')->comment('外链地址');
            $table->unsignedInteger('weight')->default(new \Illuminate\Database\Query\Expression('0'))->comment('排序(从小到大)');
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
        Schema::dropIfExists('slider');
    }
};
