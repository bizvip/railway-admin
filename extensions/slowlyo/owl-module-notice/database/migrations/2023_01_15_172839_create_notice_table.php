<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();

            $table->string('title')->comment('标题');
            $table->string('type')->comment('类型');
            $table->string('weight')->comment('权重');
            $table->string('state')->comment('状态');
            $table->text('content')->comment('内容');

            $table->timestamps();
            $table->softDeletes();

            $table->index('title');
            $table->index('type');
            $table->index('weight');
            $table->index('state');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notices');
    }
};
