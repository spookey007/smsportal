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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->boolean('type')->comment('1=email batch,2=sms batch');
            $table->dateTime('date');
            $table->bigInteger('total')->default(0);
            $table->bigInteger('total_success')->default(0);
            $table->bigInteger('total_fail')->default(0);
            $table->string('sender');
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
        Schema::dropIfExists('batches');
    }
};
