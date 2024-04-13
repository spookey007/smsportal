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
        Schema::create('sms_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('contact_id')->default(0);
            $table->string('mobile');
            $table->string('sender')->nullable();
            $table->longText('message');
            $table->boolean('status')->default(0)->comment('2=scheduled,1=completed,9=failed');
            $table->dateTime('sent_time')->nullable();
            $table->dateTime('schedule')->nullable();
            $table->text('fail_reason')->nullable();
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
        Schema::dropIfExists('sms_histories');
    }
};
