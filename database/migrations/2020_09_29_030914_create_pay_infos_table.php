<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payinfos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->default(null)->comment("訂單ID");
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('pay_platform')->default('ECPay')->comment("支付平台");
            $table->string('platform_number')->default(null)->comment("平台訂單號");
            $table->text('platform_status')->default(null)->comment("支付狀態");
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
        Schema::dropIfExists('pay_infos');
    }
}
