<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_sms_logs', function (Blueprint $table) {
            $table->bigIncrements('intSmsLogId');
            $table->string('vchType')->comment('OTP, BULK, SMS, UNICODE_OTP, UNICODE_BULK, UNICODE_SMS');
            $table->string('vchMobile');
            $table->text('txtSmsContent');
            $table->dateTime('dtmCreatedOn');
            $table->boolean('booleanStatus');
            $table->text('txtStatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_sms_logs');
    }
}
